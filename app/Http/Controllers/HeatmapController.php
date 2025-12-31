<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HeatmapController extends Controller
{
    public function index(Request $request)
    {
        // Filter Inputs
        $topicFilter = $request->input('topic', 'all');
        $sourceFilter = $request->input('source', 'all');
        $dateFilter = $request->input('date', '7_days');

        // Base Query
        $query = \App\Models\NationalIssue::query();

        if ($topicFilter !== 'all') {
            $query->where('category_issue', $topicFilter);
        }
        if ($sourceFilter !== 'all') {
            $query->where('platform', $sourceFilter);
        }

        // Apply Date Filter
        if ($dateFilter === '7_days') {
            $startDate = \Carbon\Carbon::now()->subDays(7);
        } elseif ($dateFilter === '30_days') {
            $startDate = \Carbon\Carbon::now()->subDays(30);
        } else {
            $startDate = \Carbon\Carbon::now()->subDays(7); // Default
        }
        $query->where('timestamp', '>=', $startDate);

        // --- 1. Available Filters ---
        $topics = \App\Models\NationalIssue::distinct()->pluck('category_issue');

        // --- 2. Chart: Data Movement (Group by Date & Platform) ---
        // We need separate series for each platform
        $platforms = ['News', 'Twitter', 'Instagram', 'Tiktok', 'Facebook'];
        $dataMovementSeries = [];
        $dates = [];

        // Generate date range
        $period = \Carbon\CarbonPeriod::create($startDate, '1 day', \Carbon\Carbon::now());
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        foreach ($platforms as $platform) {
            $platformData = [];
            foreach ($dates as $date) {
                // Count data for this platform on this date (respecting filters)
                $count = (clone $query)->where('platform', $platform)
                        ->whereDate('timestamp', $date)
                        ->count();
                $platformData[] = $count;
            }
            $dataMovementSeries[] = [
                'name' => $platform,
                'data' => $platformData
            ];
        }

        // --- 3. Chart: Sentiment Distribution (Donut) ---
        $sentimentStats = (clone $query)->selectRaw('sentiment_label, count(*) as count')
            ->groupBy('sentiment_label')
            ->pluck('count', 'sentiment_label')
            ->toArray();
        
        $sentimentSeries = [
            $sentimentStats['Positif'] ?? 0,
            $sentimentStats['Netral'] ?? 0,
            $sentimentStats['Negatif'] ?? 0
        ];

        // --- 4. Chart: Sentiment Data Movement (Line) ---
        $sentimentMovementSeries = [];
        $sentiments = ['Positif', 'Netral', 'Negatif'];
        
        foreach ($sentiments as $sentiment) {
            $sentimentData = [];
            foreach ($dates as $date) {
                $count = (clone $query)->where('sentiment_label', $sentiment)
                        ->whereDate('timestamp', $date)
                        ->count();
                $sentimentData[] = $count;
            }
            $sentimentMovementSeries[] = [
                'name' => $sentiment,
                'data' => $sentimentData
            ];
        }

        // --- 5. Validating Top Widgets Data ---
        // Helper function for aggregated query
        $getTopStats = function($groupColumn) use ($query) {
            return (clone $query)->select(
                    $groupColumn, 
                    \DB::raw('count(*) as total'),
                    \DB::raw("SUM(CASE WHEN sentiment_label = 'Positif' THEN 1 ELSE 0 END) as pos"),
                    \DB::raw("SUM(CASE WHEN sentiment_label = 'Netral' THEN 1 ELSE 0 END) as neu"),
                    \DB::raw("SUM(CASE WHEN sentiment_label = 'Negatif' THEN 1 ELSE 0 END) as neg")
                )
                ->groupBy($groupColumn)
                ->orderByDesc('total')
                ->take(10)
                ->get();
        };

        // Top Issue
        $topIssues = $getTopStats('category_issue');

        // Top Person (Author)
        $topPersons = $getTopStats('author_name');

        // Top Media (Platform)
        $topMedias = $getTopStats('platform');

        // Total Counts per Platform for Cards
        $totalCounts = (clone $query)->select('platform', \DB::raw('count(*) as total'))
            ->groupBy('platform')
            ->pluck('total', 'platform')
            ->toArray();

        return view('heatmap.index', compact(
            'topics', 
            'dataMovementSeries', 
            'dates', 
            'sentimentSeries', 
            'sentimentMovementSeries',
            'topIssues',
            'topPersons',
            'topMedias',
            'totalCounts',
            'request'
        ));
    }

    public function generateDemo()
    {
        // Run Python script in demo mode
        $command = "python " . base_path('scripts/sentiment_processor.py') . " --mode=demo";
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            return response()->json(['success' => true, 'message' => 'Demo data generated successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to generate demo data.', 'output' => $output], 500);
        }
    }

    public function fetchLive(Request $request)
    {
        $request->validate([
            'engine' => 'required|in:blob,gemini'
        ]);

        $engine = $request->input('engine', 'blob');
        
        // Run Python script in live mode with selected engine
        $command = "python " . base_path('scripts/sentiment_processor.py') . " --mode=live --engine=" . escapeshellarg($engine);
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            return response()->json(['success' => true, 'message' => "Live data fetched successfully using " . strtoupper($engine) . "!"]);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to fetch live data.', 'output' => $output], 500);
        }
    }

    public function clearData()
    {
        try {
            \App\Models\NationalIssue::truncate();
            return response()->json(['success' => true, 'message' => 'All data cleared successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to clear data: ' . $e->getMessage()], 500);
        }
    }
}
