<x-app-layout>
    <div class="px-6 py-8">
        <!-- Header & Controls -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">National Issue Monitoring</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Real-time sentiment tracking for strategic topics.</p>
                </div>
                
                <!-- Toolkit Bar -->
                <div class="flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-4 bg-white dark:bg-gray-800 p-2 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    
                    <!-- Sentiment Engine Toggle -->
                    <div class="flex bg-gray-100 dark:bg-gray-700 p-1 rounded-lg">
                        <label class="cursor-pointer">
                            <input type="radio" name="sentiment_engine" value="blob" class="peer sr-only" checked>
                            <span class="block px-4 py-2 text-xs font-semibold rounded-md transition-all peer-checked:bg-white peer-checked:text-emerald-600 peer-checked:shadow-sm dark:peer-checked:bg-gray-600 dark:peer-checked:text-white text-gray-500 dark:text-gray-400">
                                Blob (Fast)
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="sentiment_engine" value="gemini" class="peer sr-only">
                            <span class="block px-4 py-2 text-xs font-semibold rounded-md transition-all peer-checked:bg-white peer-checked:text-emerald-600 peer-checked:shadow-sm dark:peer-checked:bg-gray-600 dark:peer-checked:text-white text-gray-500 dark:text-gray-400">
                                Gemini AI
                            </span>
                        </label>
                    </div>

                    <!-- Fetch Button -->
                    <button onclick="runLiveFetch()" class="w-full sm:w-auto px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg text-sm transition-colors shadow-lg shadow-emerald-200 dark:shadow-none flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        Fetch Live Data
                    </button>
                    
                    <!-- More Actions Dropdown (Optional or simplified to link) -->
                    <div class="relative group">
                         <button class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                        </button>
                         <!-- Simple Dropdown Content -->
                         <div class="absolute right-0 top-full pt-2 w-48 hidden group-hover:block z-50">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                                <a href="#" onclick="runAction('{{ route('heatmap.demo') }}', 'Generating Demo Data...')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Generate Demo Data</a>
                                <a href="#" onclick="runAction('{{ route('heatmap.clear') }}', 'Clearing Data...')" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30">Clear All Data</a>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
            
            <!-- Filter Bar -->
            <div class="flex flex-wrap gap-3 mt-6">
                 <select id="topicFilter" class="bg-white border-none py-2 px-4 shadow-sm rounded-lg text-sm text-gray-600 font-medium focus:ring-2 focus:ring-emerald-500 dark:bg-gray-800 dark:text-gray-300">
                    <option value="all" {{ ($request->topic === 'all') ? 'selected' : '' }}>Topic: All</option>
                    @foreach($topics as $topic)
                        <option value="{{ $topic }}" {{ ($request->topic === $topic) ? 'selected' : '' }}>{{ $topic }}</option>
                    @endforeach
                </select>
                <select id="sourceFilter" class="bg-white border-none py-2 px-4 shadow-sm rounded-lg text-sm text-gray-600 font-medium focus:ring-2 focus:ring-emerald-500 dark:bg-gray-800 dark:text-gray-300">
                    <option value="all" {{ ($request->source === 'all') ? 'selected' : '' }}>Source: All</option>
                    <option value="News" {{ ($request->source === 'News') ? 'selected' : '' }}>News</option>
                    <option value="Twitter" {{ ($request->source === 'Twitter') ? 'selected' : '' }}>Twitter</option>
                    <option value="Instagram" {{ ($request->source === 'Instagram') ? 'selected' : '' }}>Instagram</option>
                    <option value="Facebook" {{ ($request->source === 'Facebook') ? 'selected' : '' }}>Facebook</option>
                    <option value="Tiktok" {{ ($request->source === 'Tiktok') ? 'selected' : '' }}>Tiktok</option>
                </select>
            </div>
        </div>

        <!-- Notification Area -->
        <div id="action-alert" class="hidden p-4 mb-6 text-sm text-emerald-800 rounded-xl bg-emerald-50 dark:bg-gray-800 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900" role="alert">
            <div class="flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-emerald-600" id="loading-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span class="font-semibold" id="alert-message">Processing...</span>
            </div>
        </div>

        <!-- Row 1: Key Metrics & Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Data Movement (2/3) -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Volume Trends</h3>
                <div id="chart-data-movement" class="w-full"></div>
            </div>
            
            <!-- Sentiment Gauge (1/3) -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col justify-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 text-center">Sentiment Distribution</h3>
                <div id="chart-sentiment-donut" class="w-full h-48 flex justify-center"></div>
                <div class="mt-4 grid grid-cols-3 gap-2 text-center text-xs text-gray-500">
                    <div>
                        <span class="block text-2xl font-bold text-emerald-500">{{ $sentimentSeries[0] }}</span>
                        Positif
                    </div>
                    <div>
                        <span class="block text-2xl font-bold text-gray-400">{{ $sentimentSeries[1] }}</span>
                        Netral
                    </div>
                    <div>
                        <span class="block text-2xl font-bold text-rose-500">{{ $sentimentSeries[2] }}</span>
                        Negatif
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Sentiment Over Time -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Sentiment Volatility</h3>
            <div id="chart-sentiment-movement" class="w-full"></div>
        </div>

        <!-- Row 3: Top Lists (2 Column Grid) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach([
                ['title' => 'Top Issues', 'data' => $topIssues, 'key' => 'category_issue', 'icon' => 'M7 20l4-16m2 16l4-16M6 9h14M4 15h14'],
                ['title' => 'Influential Voices', 'data' => $topPersons, 'key' => 'author_name', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ['title' => 'Active Organizations', 'data' => [], 'key' => 'org', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'], 
                ['title' => 'Top Platforms', 'data' => $topMedias, 'key' => 'platform', 'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9']
            ] as $widget)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col h-full hover:shadow-md transition-shadow">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="p-2 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg text-emerald-600 dark:text-emerald-400">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $widget['icon'] }}"></path></svg>
                        </div>
                        <h5 class="text-base font-bold text-gray-900 dark:text-white">{{ $widget['title'] }}</h5>
                    </div>
                    <span class="text-xs font-medium text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">Top 10</span>
                </div>
                
                <div class="p-0 overflow-x-auto">
                    @if(count($widget['data']) > 0)
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                            <tr>
                                <th class="px-5 py-3 font-semibold w-10">#</th>
                                <th class="px-5 py-3 font-semibold">Name</th>
                                <th class="px-5 py-3 font-semibold w-32">Sentiment</th>
                                <th class="px-5 py-3 font-semibold text-right">Vol</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @foreach($widget['data'] as $index => $item)
                            <tr class="group hover:bg-gray-50/80 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-5 py-3 font-medium text-gray-400 group-hover:text-emerald-600">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-5 py-3 font-medium text-gray-800 dark:text-gray-200">
                                    <div class="truncate max-w-[150px]" title="{{ $item->{$widget['key']} }}">
                                        {{ Str::limit($item->{$widget['key']}, 20) }}
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex h-1.5 w-full rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700">
                                        @php
                                            $total = $item->total > 0 ? $item->total : 1;
                                            $posPct = ($item->pos / $total) * 100;
                                            $neuPct = ($item->neu / $total) * 100;
                                            $negPct = ($item->neg / $total) * 100;
                                        @endphp
                                        @if($posPct > 0) <div class="h-full bg-emerald-500" style="width: {{ $posPct }}%"></div> @endif
                                        @if($neuPct > 0) <div class="h-full bg-gray-300 dark:bg-gray-500" style="width: {{ $neuPct }}%"></div> @endif
                                        @if($negPct > 0) <div class="h-full bg-rose-500" style="width: {{ $negPct }}%"></div> @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-right font-bold text-gray-700 dark:text-gray-300">
                                    {{ number_format($item->total) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="flex flex-col items-center justify-center h-48 text-gray-400">
                        <svg class="w-10 h-10 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <p class="text-sm">No Data Available</p>
                        @if($widget['title'] === 'Active Organizations')
                            <span class="text-xs text-gray-300 mt-1">Coming Soon</span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // General Chart Options - Modern Theme
            const commonOptions = {
                chart: {
                    fontFamily: 'Inter, sans-serif',
                    toolbar: { show: false },
                    background: 'transparent'
                },
                grid: {
                    borderColor: '#f3f4f6', 
                    padding: {  top: 0, right: 0, bottom: 0, left: 10 }
                },
                dataLabels: { enabled: false },
                theme: { mode: 'light' } 
            };

            // --- 1. Data Movement Chart (Area/Line) ---
            var optionsMovement = {
                ...commonOptions,
                series: @json($dataMovementSeries),
                chart: {
                    height: 320,
                    type: 'area', // Use Area for better fill effect
                    toolbar: { show: false }
                },
                options: {
                   ...commonOptions
                },
                stroke: { curve: 'smooth', width: 2 },
                fill: {
                  type: 'gradient',
                  gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] }
                },
                colors: ['#10b981', '#3b82f6', '#f43f5e', '#f59e0b', '#8b5cf6'], // Emerald First
                xaxis: {
                    categories: @json($dates),
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#9ca3af', fontSize: '11px' } }
                },
                yaxis: {
                    labels: { style: { colors: '#9ca3af', fontSize: '11px' } }
                }
            };
            var chartMovement = new ApexCharts(document.querySelector("#chart-data-movement"), optionsMovement);
            chartMovement.render();

            // --- 2. Total Sentiment (Donut) ---
            var optionsSentiment = {
                ...commonOptions,
                series: @json($sentimentSeries), 
                chart: {
                    type: 'donut',
                    height: 250 // slightly smaller to fit
                },
                labels: ['Positif', 'Netral', 'Negatif'],
                colors: ['#10b981', '#9ca3af', '#f43f5e'],
                plotOptions: {
                    pie: {
                      donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            name: { show: false },
                            value: { fontSize: '24px', fontWeight: 700, color: '#374151', offsetY: 8 }
                        }
                      }
                    }
                },
                stroke: { show: false },
                legend: { show: false }
            };
            var chartSentiment = new ApexCharts(document.querySelector("#chart-sentiment-donut"), optionsSentiment);
            chartSentiment.render();

            // --- 3. Sentiment Data Movement (Line) ---
            var optionsSentimentMove = {
                ...commonOptions,
                series: @json($sentimentMovementSeries),
                chart: {
                    height: 250,
                    type: 'line',
                    toolbar: { show: false }
                },
                stroke: { curve: 'monotoneCubic', width: 2 },
                colors: ['#10b981', '#9ca3af', '#f43f5e'],
                xaxis: {
                    categories: @json($dates),
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#9ca3af', fontSize: '10px' } }
                },
                 yaxis: {
                    labels: { style: { colors: '#9ca3af', fontSize: '10px' } }
                }
            };
            var chartSentimentMove = new ApexCharts(document.querySelector("#chart-sentiment-movement"), optionsSentimentMove);
            chartSentimentMove.render();

            // Filter Functionality
            const topicFilter = document.getElementById('topicFilter');
            const sourceFilter = document.getElementById('sourceFilter');
            
            function applyFilters() {
                const topic = topicFilter.value;
                const source = sourceFilter.value;
                window.location.href = `?topic=${topic}&source=${source}`;
            }

            topicFilter.addEventListener('change', applyFilters);
            sourceFilter.addEventListener('change', applyFilters);
        });

        function runLiveFetch() {
            const engine = document.querySelector('input[name="sentiment_engine"]:checked').value;
            const engineName = engine === 'gemini' ? 'AI Gemini' : 'Standard Blob';
            const spinner = document.getElementById('loading-spinner');
            
            if(!confirm(`Fetch Live Data using ${engineName}? This might take a while.`)) return;

            const alertBox = document.getElementById('action-alert');
            const alertMsg = document.getElementById('alert-message');
            
            // Show Loading
            alertBox.classList.remove('hidden', 'text-emerald-800', 'bg-emerald-50', 'text-rose-800', 'bg-rose-50');
            alertBox.classList.add('flex', 'text-emerald-800', 'bg-emerald-50'); // Use Emerald for active state too
            spinner.classList.remove('hidden');
            spinner.classList.remove('text-indigo-600');
            spinner.classList.add('text-emerald-600');
            alertMsg.innerText = `Fetching Live Data with ${engineName}... Please wait.`;
            
            fetch('{{ route("heatmap.live") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ engine: engine })
            })
            .then(response => response.json())
            .then(data => {
                spinner.classList.add('hidden');
                if(data.success) {
                    alertBox.classList.remove('text-emerald-800', 'bg-emerald-50');
                    alertBox.classList.add('text-emerald-800', 'bg-emerald-50'); // Keep consistent
                    alertMsg.innerText = data.message + " Reloading...";
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alertBox.classList.remove('text-emerald-800', 'bg-emerald-50');
                    alertBox.classList.add('text-rose-800', 'bg-rose-50');
                    alertMsg.innerText = "Error: " + data.message;
                }
            })
            .catch(error => {
                spinner.classList.add('hidden');
                console.error('Error:', error);
                alertBox.classList.remove('text-emerald-800', 'bg-emerald-50');
                alertBox.classList.add('text-rose-800', 'bg-rose-50');
                alertMsg.innerText = "Network or server error.";
            });
        }

        function runAction(url, loadingMsg) {
            if(!confirm("Are you sure?")) return;

            const alertBox = document.getElementById('action-alert');
            const alertMsg = document.getElementById('alert-message');
            const spinner = document.getElementById('loading-spinner');
            
            // Show Loading
             alertBox.classList.remove('hidden', 'text-emerald-800', 'bg-emerald-50', 'text-rose-800', 'bg-rose-50');
            alertBox.classList.add('flex', 'text-emerald-800', 'bg-emerald-50');
            spinner.classList.remove('hidden');
            spinner.classList.remove('text-indigo-600');
            spinner.classList.add('text-emerald-600');
            alertMsg.innerText = loadingMsg;
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                spinner.classList.add('hidden');
                if(data.success) {
                    alertBox.classList.remove('text-emerald-800', 'bg-emerald-50');
                    alertBox.classList.add('text-emerald-800', 'bg-emerald-50');
                    alertMsg.innerText = data.message + " Reloading...";
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alertBox.classList.remove('text-emerald-800', 'bg-emerald-50');
                    alertBox.classList.add('text-rose-800', 'bg-rose-50');
                    alertMsg.innerText = "Error: " + data.message;
                }
            })
            .catch(error => {
                spinner.classList.add('hidden');
                console.error('Error:', error);
                alertBox.classList.remove('text-emerald-800', 'bg-emerald-50');
                alertBox.classList.add('text-rose-800', 'bg-rose-50');
                alertMsg.innerText = "Network error.";
            });
        }
    </script>
</x-app-layout>
