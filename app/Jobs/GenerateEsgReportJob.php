<?php

namespace App\Jobs;

use App\Models\EsgReport;
use App\Services\EsgReportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateEsgReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 1;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 1200; // 20 minutes

    /**
     * The report ID to process
     */
    protected int $reportId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $reportId)
    {
        $this->reportId = $reportId;
    }

    /**
     * Execute the job.
     */
    public function handle(EsgReportService $service): void
    {
        $report = EsgReport::find($this->reportId);

        if (!$report) {
            Log::error("ESG Report not found: {$this->reportId}");
            return;
        }

        // Skip if already processed
        if (in_array($report->status, ['completed', 'processing'])) {
            Log::info("ESG Report {$this->reportId} already processed or in progress");
            return;
        }

        $service->generateReport($report);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $report = EsgReport::find($this->reportId);

        if ($report) {
            $report->markAsFailed('Job failed: ' . $exception->getMessage());
        }

        Log::error('GenerateEsgReportJob failed', [
            'report_id' => $this->reportId,
            'error' => $exception->getMessage(),
        ]);
    }
}
