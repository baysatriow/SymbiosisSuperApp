<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Document;
use App\Models\DocumentField;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    protected $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    /* ============================================================
     * MANAJEMEN USER — APPROVE / REJECT
     * ============================================================ */
    public function approveUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->status !== 'pending_admin_review') {
            return back()->with('error', 'User ini tidak dalam status pending.');
        }

        $user->update(['status' => 'active']);

        $msg = "Halo {$user->full_name}, akun Symbiosis Anda telah DISETUJUI oleh Admin. Silakan login.";
        $this->fonnte->sendWhatsApp($user->phone_number, $msg);

        return back()->with('success', 'User berhasil disetujui.');
    }

    public function rejectUser($id)
    {
        $user = User::findOrFail($id);

        $user->update(['status' => 'rejected']);

        $msg = "Halo {$user->full_name}, mohon maaf pendaftaran akun Symbiosis Anda DITOLAK.";
        $this->fonnte->sendWhatsApp($user->phone_number, $msg);

        return back()->with('success', 'User ditolak.');
    }

    /* ============================================================
     * MANAJEMEN DOKUMEN — APPROVE / REJECT
     * ============================================================ */
    public function approveDocument($id)
    {
        $doc = Document::with(['user', 'subfield'])->findOrFail($id);

        $doc->update([
            'status'        => 'approved',
            'verified_by'   => Auth::id(),
            'verified_at'   => now(),
            'tier'          => 'green',
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Dokumen berhasil disetujui.');
    }

    public function rejectDocument(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255'
        ]);

        $doc = Document::with('user')->findOrFail($id);

        $doc->update([
            'status'        => 'rejected',
            'verified_by'   => Auth::id(),
            'verified_at'   => now(),
            'rejection_reason' => $request->rejection_reason,
            'tier'          => 'black',
        ]);

        $msg = "Dokumen '{$doc->original_filename}' DITOLAK. Alasan: {$request->rejection_reason}.";
        $this->fonnte->sendWhatsApp($doc->user->phone_number, $msg);

        return back()->with('success', 'Dokumen ditolak.');
    }

    /* ============================================================
     * PREVIEW FILE
     * ============================================================ */
    public function viewDocument($id)
    {
        $doc = Document::findOrFail($id);

        if (Auth::user()->role !== 'admin' && Auth::id() !== $doc->user_id) {
            abort(403);
        }

        $path = storage_path("app/public/{$doc->storage_path}");

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file($path);
    }

    /* ============================================================
     * LIST USER UNTUK KELOLA DOKUMEN
     * ============================================================ */
    public function listUsersForDocuments(Request $request)
    {
        $search = $request->input('search');

        $stats = [
            'total_uploaded' => Document::count(),
            'pending'        => Document::where('status', 'pending')->count(),
            'rejected'       => Document::where('status', 'rejected')->count(),
            'storage_bytes'  => Document::sum('size_bytes_original'),
        ];

        $users = User::where('role', 'user')
            ->where('status', 'active')
            ->when($search, function ($query, $search) {
                return $query->where('full_name', 'like', "%$search%")
                             ->orWhere('email', 'like', "%$search%");
            })
            ->withCount(['userProfile', 'companyProfile', 'documents'])
            ->paginate(10);

        return view('admin.documents.users', compact('users', 'stats'));
    }

    /* ============================================================
     * DETAIL DOKUMEN USER TERTENTU
     * ============================================================ */
    public function showUserDocuments($userId)
    {
        $targetUser = User::with(['userProfile', 'companyProfile'])->findOrFail($userId);

        // Struktur Field (Standard + Custom)
        $fields = DocumentField::with([
            'subfields' => function($query) use ($userId) {
                $query->where(function($q) use ($userId) {
                    $q->whereNull('user_id')   // Standard Admin
                      ->orWhere('user_id', $userId); // Custom User
                })
                ->orderBy('sort_order')
                ->orderBy('created_at');
            }
        ])->orderBy('sort_order')->get();

        $userDocuments = Document::where('user_id', $userId)
            ->get()
            ->keyBy('subfield_id');

        $stats = [
            'total'    => $userDocuments->count(),
            'approved' => $userDocuments->where('status', 'approved')->count(),
            'pending'  => $userDocuments->where('status', 'pending')->count(),
            'rejected' => $userDocuments->where('status', 'rejected')->count(),
        ];

        return view('admin.documents.show', compact('targetUser', 'fields', 'userDocuments', 'stats'));
    }

    /* ============================================================
     * DELETE DOKUMEN (ADMIN)
     * ============================================================ */
    public function deleteDocument($id)
    {
        $doc = Document::findOrFail($id);

        if (Storage::disk('public')->exists($doc->storage_path)) {
            Storage::disk('public')->delete($doc->storage_path);
        }

        $doc->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    /* ============================================================
     * USER CRUD — LIST
     * ============================================================ */
    public function manageUsers(Request $request)
    {
        $search = $request->input('search');

        $users = User::when($search, function ($query, $search) {
                return $query->where('full_name', 'like', "%$search%")
                             ->orWhere('email', 'like', "%$search%")
                             ->orWhere('username', 'like', "%$search%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /* ============================================================
     * USER CRUD — CREATE
     * ============================================================ */
    public function storeUser(Request $request)
    {
        // Normalisasi nomor HP
        $cleanPhone = preg_replace('/\D/', '', $request->phone_number);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        } elseif (!str_starts_with($cleanPhone, '62')) {
            $cleanPhone = '62' . $cleanPhone;
        }
        $request->merge(['phone_number' => $cleanPhone]);

        $request->validate([
            'full_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'username'     => 'required|string|unique:users,username',
            'phone_number' => 'required|string|unique:users,phone_number',
            'password'     => 'required|string|min:8',
            'role'         => 'required|in:admin,user',
            'status'       => 'required|in:active,pending_admin_review,frozen,rejected',
        ]);

        User::create([
            'full_name'     => $request->full_name,
            'email'         => $request->email,
            'username'      => $request->username,
            'phone_number'  => $request->phone_number,
            'password_hash' => Hash::make($request->password),
            'role'          => $request->role,
            'status'        => $request->status,
        ]);

        return back()->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    /* ============================================================
     * USER CRUD — UPDATE
     * ============================================================ */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Normalisasi nomor HP
        $cleanPhone = preg_replace('/\D/', '', $request->phone_number);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        } elseif (!str_starts_with($cleanPhone, '62')) {
            $cleanPhone = '62' . $cleanPhone;
        }
        $request->merge(['phone_number' => $cleanPhone]);

        $request->validate([
            'full_name'    => 'required|string|max:255',
            'email'        => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'username'     => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'role'         => 'required|in:admin,user',
            'status'       => 'required|in:active,pending_admin_review,frozen,rejected,awaiting_otp',
            'password'     => 'nullable|string|min:8',
        ]);

        $data = [
            'full_name'    => $request->full_name,
            'email'        => $request->email,
            'username'     => $request->username,
            'phone_number' => $request->phone_number,
            'role'         => $request->role,
            'status'       => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password_hash'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /* ============================================================
     * USER CRUD — DELETE
     * ============================================================ */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
