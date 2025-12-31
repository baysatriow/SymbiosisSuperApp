<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\EsgReport;
use App\Models\Document;
use App\Services\EsgReportService;
use App\Jobs\GenerateEsgReportJob;

class EsgReportController extends Controller
{
    protected EsgReportService $esgService;

    public function __construct(EsgReportService $esgService)
    {
        $this->esgService = $esgService;
    }

    /* ============================================================
     * INDEX — LIST ALL ESG REPORTS FOR USER
     * ============================================================ */
    public function index()
    {
        $user = Auth::user();

        // Gatekeeper: check profile completion
        if (
            !$user->userProfile?->is_completed ||
            !$user->companyProfile?->is_completed
        ) {
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'Lengkapi profil untuk akses fitur ESG Report.');
        }

        $reports = EsgReport::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        // Redirect to create page if no reports exist
        if ($reports->isEmpty()) {
            return redirect()->route('user.esg.create');
        }

        return view('user.esg.index', compact('reports'));
    }

    /* ============================================================
     * CREATE — SHOW FORM TO START GENERATION
     * ============================================================ */
    public function create()
    {
        $user = Auth::user();

        // Check profile
        if (
            !$user->userProfile?->is_completed ||
            !$user->companyProfile?->is_completed
        ) {
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'Lengkapi profil untuk akses fitur ESG Report.');
        }

        // Get user's approved documents
        $documents = Document::where('user_id', $user->id)
            ->where('status', 'approved')
            ->with('subfield.field')
            ->get();

        // Get report structure for preview
        $reportStructure = $this->esgService->getReportStructure();
        $totalSubchapters = $this->esgService->getTotalSubchapters();

        // Check if there's already a pending/processing report
        $pendingReport = EsgReport::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'processing'])
            ->first();

        return view('user.esg.create', compact(
            'documents',
            'reportStructure',
            'totalSubchapters',
            'pendingReport'
        ));
    }

    /* ============================================================
     * STORE — START REPORT GENERATION
     * ============================================================ */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'output_format' => 'required|in:docx,pdf',
        ]);

        $user = Auth::user();

        // Check for existing pending/processing reports
        $existingReport = EsgReport::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'processing'])
            ->first();

        if ($existingReport) {
            return redirect()
                ->route('user.esg.show', $existingReport->id)
                ->with('warning', 'Anda memiliki laporan yang sedang diproses. Silakan tunggu hingga selesai.');
        }

        // Check if user has at least 1 approved document
        $hasDocuments = Document::where('user_id', $user->id)
            ->where('status', 'approved')
            ->exists();

        if (!$hasDocuments) {
            return back()
                ->with('error', 'Anda harus memiliki minimal 1 dokumen yang sudah disetujui untuk membuat laporan ESG.');
        }

        // Create report
        $report = EsgReport::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'output_format' => $request->output_format,
            'status' => 'pending',
            'progress' => [
                'current_chapter' => 0,
                'total_chapters' => 7,
                'current_section' => 'Menunggu...',
                'percentage' => 0,
            ],
        ]);

        // Dispatch job to queue
        GenerateEsgReportJob::dispatch($report->id);

        return redirect()
            ->route('user.esg.show', $report->id)
            ->with('success', 'Laporan ESG sedang diproses. Silakan pantau progress di halaman ini.');
    }

    /* ============================================================
     * SHOW — VIEW REPORT DETAIL & PROGRESS
     * ============================================================ */
    public function show($id)
    {
        $report = EsgReport::where('user_id', Auth::id())
            ->findOrFail($id);

        $reportStructure = $this->esgService->getReportStructure();

        return view('user.esg.show', compact('report', 'reportStructure'));
    }

    /* ============================================================
     * PROGRESS — API ENDPOINT FOR POLLING
     * ============================================================ */
    public function progress($id)
    {
        $report = EsgReport::where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'status' => $report->status,
            'status_label' => $report->status_label,
            'status_color' => $report->status_color,
            'progress' => $report->progress,
            'file_path' => $report->file_path,
            'error_message' => $report->error_message,
            'completed_at' => $report->completed_at?->format('d M Y H:i'),
            'download_url' => $report->file_path
                ? route('user.esg.download', $report->id)
                : null,
        ]);
    }

    /* ============================================================
     * DOWNLOAD — DOWNLOAD GENERATED FILE
     * ============================================================ */
    public function download($id)
    {
        $report = EsgReport::where('user_id', Auth::id())
            ->findOrFail($id);

        if ($report->status !== 'completed' || !$report->file_path) {
            return back()->with('error', 'Laporan belum selesai atau file tidak tersedia.');
        }

        $fullPath = Storage::disk('public')->path($report->file_path);

        if (!file_exists($fullPath)) {
            return back()->with('error', 'File tidak ditemukan di server.');
        }

        $filename = 'Laporan_ESG_' . str_replace(' ', '_', $report->title) . '.' . $report->output_format;

        return response()->download($fullPath, $filename);
    }

    /* ============================================================
     * DESTROY — DELETE REPORT
     * ============================================================ */
    public function destroy($id)
    {
        $report = EsgReport::where('user_id', Auth::id())
            ->findOrFail($id);

        // Delete file if exists
        if ($report->file_path && Storage::disk('public')->exists($report->file_path)) {
            Storage::disk('public')->delete($report->file_path);
        }

        $report->delete();

        return redirect()
            ->route('user.esg.index')
            ->with('success', 'Laporan ESG berhasil dihapus.');
    }
}
