<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Document;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /* ============================================================
     * USER DASHBOARD
     * ============================================================ */
    public function userDashboard()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Load profil lengkap
        $user->load(['userProfile', 'companyProfile']);

        // Cek kelengkapan profil
        $isProfileComplete = $user->userProfile?->is_completed ?? false;
        $isCompanyComplete = $user->companyProfile?->is_completed ?? false;
        $isAllComplete = $isProfileComplete && $isCompanyComplete;

        // Statistik dokumen
        $stats = [
            'total_documents'   => DB::table('documents')->where('user_id', $userId)->count(),
            'approved'          => DB::table('documents')->where('user_id', $userId)->where('status', 'approved')->count(),
            'pending'           => DB::table('documents')->where('user_id', $userId)->where('status', 'pending')->count(),
            'rejected'          => DB::table('documents')->where('user_id', $userId)->where('status', 'rejected')->count(),
            'total_size_bytes'  => DB::table('documents')->where('user_id', $userId)->sum('size_bytes_original'),
        ];

        // Ambil tier dokumen terbaru (approved)
        $currentTier = DB::table('documents')
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->select('tier')
            ->orderByDesc('updated_at')
            ->first();

        $tierLabel = $currentTier ? $currentTier->tier : 'Belum Ada';

        return view('user.dashboard', compact(
            'user',
            'isAllComplete',
            'stats',
            'tierLabel'
        ));
    }

    /* ============================================================
     * ADMIN DASHBOARD
     * ============================================================ */
    public function adminDashboard(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Statistik global
        $stats = [
            'total_users'         => User::where('role', 'user')->count(),
            'active_users'        => User::where('role', 'user')->where('status', 'active')->count(),
            'pending_users'       => User::where('role', 'user')->where('status', 'pending_admin_review')->count(),
            'total_documents'     => DB::table('documents')->count(),
            'pending_documents'   => DB::table('documents')->where('status', 'pending')->count(),
            'total_storage_bytes' => DB::table('documents')->sum('size_bytes_original'),
        ];

        // User pending (maks 50)
        $pendingUsers = User::where('status', 'pending_admin_review')
            ->orderBy('created_at', 'asc')
            ->limit(50)
            ->get();

        // Dokumen pending (maks 50)
        $pendingDocuments = DB::table('documents')
            ->join('users', 'documents.user_id', '=', 'users.id')
            ->select(
                'documents.id',
                'documents.original_filename',
                'documents.created_at',
                'documents.updated_at',
                'users.full_name as uploader_name',
                'users.email as uploader_email'
            )
            ->where('documents.status', 'pending')
            ->orderBy('documents.created_at', 'asc')
            ->limit(50)
            ->get();

        // Grafik (7 hari / 1 bulan / 1 tahun)
        $filter = $request->input('filter', '7_days');
        $chartData = $this->getChartData($filter);

        return view('admin.dashboard', compact(
            'stats',
            'pendingUsers',
            'pendingDocuments',
            'chartData',
            'filter'
        ));
    }

    /* ============================================================
     * HELPER: GENERATE CHART DATA
     * ============================================================ */
    private function getChartData($filter)
    {
        $labels = [];
        $usersData = [];
        $docsData = [];

        if ($filter === '1_month') {
            // 30 hari terakhir
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $format = $date->format('Y-m-d');

                $labels[] = $date->format('d M');
                $usersData[] = User::whereDate('created_at', $format)
                    ->where('role', 'user')
                    ->count();
                $docsData[] = Document::whereDate('created_at', $format)->count();
            }

        } elseif ($filter === '1_year') {
            // 12 bulan terakhir
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $month = $date->month;
                $year  = $date->year;

                $labels[] = $date->format('M Y');
                $usersData[] = User::whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->where('role', 'user')
                    ->count();
                $docsData[] = Document::whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->count();
            }

        } else {
            // Default: 7 hari terakhir
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $format = $date->format('Y-m-d');

                $labels[] = $date->format('d M');
                $usersData[] = User::whereDate('created_at', $format)
                    ->where('role', 'user')
                    ->count();
                $docsData[] = Document::whereDate('created_at', $format)->count();
            }
        }

        return [
            'labels'    => $labels,
            'users'     => $usersData,
            'documents' => $docsData,
        ];
    }
}
