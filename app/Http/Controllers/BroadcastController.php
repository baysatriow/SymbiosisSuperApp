<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MessageTemplate;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BroadcastController extends Controller
{
    protected $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    /* ============================================================
     * INDEX — LIST USER + TEMPLATE
     * ============================================================ */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::with(['userProfile', 'companyProfile'])
            ->where('role', 'user')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('phone_number', 'like', "%$search%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $totalUsers = User::where('role', 'user')->count();
        $templates = MessageTemplate::all();

        return view('admin.broadcast.index', compact('users', 'templates', 'totalUsers'));
    }

    /* ============================================================
     * SEND BROADCAST
     * ============================================================ */
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        if (!$request->has('send_all') && empty($request->user_ids)) {
            return back()->with('error', 'Harap pilih penerima pesan.');
        }

        // Tentukan target user (all atau selected)
        if ($request->has('send_all') && $request->send_all == '1') {
            $targetQuery = User::with(['userProfile', 'companyProfile'])
                ->where('role', 'user');
        } else {
            $targetQuery = User::with(['userProfile', 'companyProfile'])
                ->whereIn('id', $request->user_ids);
        }

        $successCount = 0;
        $failCount = 0;

        // Broadcast chunked (100 per batch)
        $targetQuery->chunk(100, function ($users) use ($request, &$successCount, &$failCount) {
            foreach ($users as $user) {
                $personalMessage = $this->parseMessage($request->message, $user);
                $result = $this->fonnte->sendWhatsApp($user->phone_number, $personalMessage);

                $result ? $successCount++ : $failCount++;

                usleep(500000); // jeda 0.5 detik
            }
        });

        $msg = "Broadcast selesai. Berhasil: $successCount, Gagal: $failCount.";

        return back()->with($failCount > 0 ? 'warning' : 'success', $msg);
    }

    /* ============================================================
     * TEMPLATE — STORE
     * ============================================================ */
    public function storeTemplate(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255|unique:message_templates,name',
            'content' => 'required|string',
        ], [
            'name.unique' => 'Nama template sudah digunakan.',
        ]);

        MessageTemplate::create($request->only('name', 'content'));

        return back()->with('success', 'Template pesan berhasil disimpan.');
    }

    /* ============================================================
     * TEMPLATE — DELETE
     * ============================================================ */
    public function deleteTemplate($id)
    {
        MessageTemplate::findOrFail($id)->delete();
        return back()->with('success', 'Template berhasil dihapus.');
    }

    /* ============================================================
     * HELPER — PARSE MESSAGE TEMPLATE
     * ============================================================ */
    private function parseMessage($content, $user)
    {
        // Data User Dasar
        $name      = $user->full_name;
        $username  = $user->username;
        $email     = $user->email;
        $phone     = $user->phone_number;
        $status    = ucfirst($user->status);
        $joinDate  = $user->created_at->format('d M Y');

        // Data Profil User
        $jobTitle  = $user->userProfile->job_title ?? '-';
        $jobSector = $user->userProfile->job_sector ?? '-';

        // Data Perusahaan
        $companyName   = $user->companyProfile->company_name ?? 'Perusahaan Anda';
        $legalType     = $user->companyProfile->legal_entity_type ?? '';
        $companyCity   = $user->companyProfile->city ?? '-';
        $companySector = $user->companyProfile->sector ?? '-';

        // Mapping Variabel Template
        $replacements = [
            '{name}'          => $name,
            '{username}'      => $username,
            '{email}'         => $email,
            '{phone}'         => $phone,
            '{status}'        => $status,
            '{join_date}'     => $joinDate,
            '{job_title}'     => $jobTitle,
            '{job_sector}'    => $jobSector,
            '{company}'       => $companyName,
            '{legal_type}'    => $legalType,
            '{company_full}'  => trim("$legalType $companyName"),
            '{city}'          => $companyCity,
            '{company_sector}' => $companySector,

            // Variabel Sistem
            '{link_symbiosis}' => url('/'),
            '{nama_system}'    => 'Symbiosis SuperApp',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }
}
