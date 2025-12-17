<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\DocumentField;
use App\Models\DocumentSubfield;
use App\Models\Document;

class DocumentController extends Controller
{
    /* ============================================================
     * INDEX — LIST SEMUA FIELD DAN DOKUMEN USER
     * ============================================================ */
    public function index()
    {
        $user = Auth::user();

        // Pastikan profil lengkap
        if (
            !$user->userProfile?->is_completed ||
            !$user->companyProfile?->is_completed
        ) {
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'Harap lengkapi profil Anda sebelum mengakses dokumen.');
        }

        // Field dan subfield (standard + custom user)
        $fields = DocumentField::with([
            'subfields' => function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->whereNull('user_id')       // subfield default
                      ->orWhere('user_id', $user->id); // custom user
                })
                ->orderBy('sort_order')
                ->orderBy('created_at');
            }
        ])
        ->orderBy('sort_order')
        ->get();

        // Dokumen user, keyed by subfield_id
        $myDocuments = Document::where('user_id', $user->id)
            ->get()
            ->keyBy('subfield_id');

        return view('user.documents.index', compact('fields', 'myDocuments'));
    }

    /* ============================================================
     * UPLOAD — DOKUMEN STANDARD (SUBFIELD EXISTING)
     * ============================================================ */
    public function upload(Request $request)
    {
        $request->validate([
            'subfield_id'   => 'required|exists:document_subfields,id',
            'document_file' => 'required|file|mimes:pdf|max:20480',
            'display_name'  => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $file = $request->file('document_file');

        // Penamaan file final (gunakan display_name atau nama asli)
        $rawName   = $request->display_name ?: pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $finalName = Str::finish($rawName, '.pdf');

        DB::beginTransaction();
        try {
            // File storage path
            $storageFilename = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
            $path = $file->storeAs("documents/{$user->id}", $storageFilename, 'public');

            // Cek apakah dokumen untuk subfield ini sudah ada
            $existingDoc = Document::where('user_id', $user->id)
                ->where('subfield_id', $request->subfield_id)
                ->first();

            if ($existingDoc) {
                // Hapus file lama
                if (Storage::disk('public')->exists($existingDoc->storage_path)) {
                    Storage::disk('public')->delete($existingDoc->storage_path);
                }

                // Update dokumen
                $existingDoc->update([
                    'storage_path'        => $path,
                    'original_filename'   => $finalName,
                    'mime_type'           => $file->getMimeType(),
                    'size_bytes_original' => $file->getSize(),
                    'status'              => 'pending',
                    'rejection_reason'    => null,
                    'verified_at'         => null,
                    'uploaded_at'         => now(),
                ]);

            } else {
                // Insert dokumen baru
                Document::create([
                    'user_id'            => $user->id,
                    'subfield_id'        => $request->subfield_id,
                    'storage_path'       => $path,
                    'original_filename'  => $finalName,
                    'mime_type'          => $file->getMimeType(),
                    'size_bytes_original'=> $file->getSize(),
                    'status'             => 'pending',
                    'tier'               => 'black',
                    'checksum_sha256' => Str::random(64),
                ]);
            }

            DB::commit();
            return back()->with('success', "Dokumen berhasil diunggah dengan nama: $finalName");

        } catch (\Exception $e) {
            DB::rollBack();

            // Jika file sudah tersimpan ketika error, hapus
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /* ============================================================
     * UPLOAD — DOKUMEN CUSTOM (BUAT SUBFIELD BARU)
     * ============================================================ */
    public function uploadCustom(Request $request)
    {
        $request->validate([
            'field_id'      => 'required|exists:document_fields,id',
            'custom_name'   => 'required|string|max:255',
            'document_file' => 'required|file|mimes:pdf|max:20480',
            'display_name'  => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $file = $request->file('document_file');

        // Penamaan file final
        $rawName   = $request->display_name ?: pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $finalName = Str::finish($rawName, '.pdf');

        DB::beginTransaction();
        try {
            // Buat subfield custom milik user
            $subfield = DocumentSubfield::create([
                'field_id'      => $request->field_id,
                'name'          => $request->custom_name,
                'description'   => 'Dokumen tambahan oleh pengguna.',
                'required'      => false,
                'max_size_mb'   => 20,
                'is_custom'     => true,
                'user_id'       => $user->id,
            ]);

            // Simpan file
            $storageFilename = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
            $path = $file->storeAs("documents/{$user->id}", $storageFilename, 'public');

            // Simpan dokumen baru
            Document::create([
                'user_id'            => $user->id,
                'subfield_id'        => $subfield->id,
                'storage_path'       => $path,
                'original_filename'  => $finalName,
                'mime_type'          => $file->getMimeType(),
                'size_bytes_original'=> $file->getSize(),
                'status'             => 'pending',
                'tier'               => 'black',
                'checksum_sha256' => Str::random(64),
            ]);

            DB::commit();
            return back()->with('success', 'Dokumen tambahan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menambahkan dokumen: ' . $e->getMessage());
        }
    }
}
