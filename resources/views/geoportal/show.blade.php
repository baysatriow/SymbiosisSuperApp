<x-app-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-100px)]">

        <!-- PETA (Full Interactive) -->
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg shadow-sm p-1 h-full">
            <div id="map" class="w-full h-full rounded-lg z-0"></div>
        </div>

        <!-- DETAIL DATA -->
        <div class="lg:col-span-1 flex flex-col h-full overflow-hidden">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6 flex-1 overflow-y-auto custom-scrollbar">

                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $geo->title }}</h2>
                    <a href="{{ route('geoportal.index') }}" class="text-sm text-gray-500 hover:text-primary-600">Kembali</a>
                </div>

                <p class="text-sm text-gray-500 mb-6 dark:text-gray-400">{{ $geo->description ?? 'Tidak ada deskripsi.' }}</p>

                @if(Auth::user()->role === 'admin')
                    <div class="p-3 bg-blue-50 rounded-lg mb-6 border border-blue-100">
                        <p class="text-xs text-blue-600 font-bold uppercase mb-1">Diposting Oleh</p>
                        <div class="text-sm font-medium text-blue-900">{{ $geo->user->full_name }}</div>
                        <div class="text-xs text-blue-800">{{ $geo->user->email }}</div>
                    </div>
                @endif

                <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase border-b pb-2 mb-4">Data Properti</h3>

                <div class="space-y-0">
                    <!-- Tabel Data Fluid -->
                    <table class="w-full text-sm text-left">
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @if($geo->properties)
                                @foreach($geo->properties as $key => $val)
                                    <tr>
                                        <td class="py-3 font-medium text-gray-500 dark:text-gray-400 w-1/3">{{ $key }}</td>
                                        <td class="py-3 font-bold text-gray-900 dark:text-white text-right">{{ $val }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td class="py-3 text-center text-gray-400">Tidak ada data properti.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="mt-8">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase border-b pb-2 mb-4">Titik Koordinat</h3>
                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs font-mono max-h-40 overflow-y-auto">
                        @foreach($geo->coordinates as $idx => $coord)
                            <div class="flex justify-between mb-1">
                                <span>Titik {{ $idx + 1 }}</span>
                                <span>{{ number_format($coord[0], 6) }}, {{ number_format($coord[1], 6) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load Data dari Server
            const coordinates = {!! json_encode($geo->coordinates) !!};
            const title = "{{ $geo->title }}";

            // Init Map
            const map = L.map('map');
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            if (coordinates && coordinates.length > 0) {
                // Gambar Poligon
                const polygon = L.polygon(coordinates, {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5
                }).addTo(map);

                // Tambah Pop up
                polygon.bindPopup(`<b>${title}</b>`);

                // Fit Zoom ke area poligon
                map.fitBounds(polygon.getBounds());

                // Tambah Marker di setiap titik (Opsional, agar jelas)
                coordinates.forEach((coord, i) => {
                    L.circleMarker(coord, {radius: 5, color: 'blue'}).addTo(map)
                     .bindTooltip(`Titik ${i+1}`);
                });
            } else {
                map.setView([-0.7893, 113.9213], 5); // Default Indonesia
            }
        });
    </script>
</x-app-layout>
