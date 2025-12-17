<x-app-layout>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KOLOM KIRI: PETA (2/3 Layar) -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-4 h-full flex flex-col">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-bold text-gray-900 dark:text-white">Peta Input Koordinat</h3>
                    <button type="button" id="btn-clear-map" class="text-xs text-red-600 hover:underline">Reset Peta</button>
                </div>

                <!-- MAP CONTAINER -->
                <div id="map" class="w-full h-[500px] rounded-lg border border-gray-300 z-0"></div>

                <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">
                    *Klik pada peta untuk menambahkan titik koordinat. Titik akan otomatis terhubung membentuk area (poligon).
                </p>
            </div>
        </div>

        <!-- KOLOM KANAN: FORM INPUT FLUID -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-4">Data Properti</h3>

                <form action="{{ route('geoportal.store') }}" method="POST" id="geoForm">
                    @csrf
                    <!-- Hidden Input Koordinat (JSON) -->
                    <input type="hidden" name="coordinates" id="coordinates_input">

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Lokasi / Perusahaan</label>
                        <input type="text" name="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="PT. Contoh Tambang" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi (Opsional)</label>
                        <textarea name="description" rows="2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>

                    <hr class="my-4 border-gray-200 dark:border-gray-700">

                    <div class="flex justify-between items-center mb-2">
                        <label class="text-sm font-bold text-gray-900 dark:text-white">Data Detail (Fluid)</label>
                        <button type="button" id="btn-add-field" class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded hover:bg-blue-200 font-bold">+ Tambah Field</button>
                    </div>

                    <!-- Container Field Dinamis -->
                    <div id="dynamic-fields-container" class="space-y-3 max-h-60 overflow-y-auto pr-1 custom-scrollbar">
                        <!-- Default Fields (Bisa dihapus user) -->
                        <div class="flex gap-2 items-center field-row">
                            <input type="text" name="keys[]" value="Provinsi" class="w-1/3 bg-gray-50 border border-gray-300 text-xs rounded p-2 dark:bg-gray-700 dark:text-white" placeholder="Nama Field">
                            <input type="text" name="values[]" class="w-full bg-gray-50 border border-gray-300 text-xs rounded p-2 dark:bg-gray-700 dark:text-white" placeholder="Isi Data">
                            <button type="button" class="text-red-500 hover:text-red-700 btn-remove-field"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </div>
                        <div class="flex gap-2 items-center field-row">
                            <input type="text" name="keys[]" value="Luas Lahan" class="w-1/3 bg-gray-50 border border-gray-300 text-xs rounded p-2 dark:bg-gray-700 dark:text-white" placeholder="Nama Field">
                            <input type="text" name="values[]" class="w-full bg-gray-50 border border-gray-300 text-xs rounded p-2 dark:bg-gray-700 dark:text-white" placeholder="Isi Data">
                            <button type="button" class="text-red-500 hover:text-red-700 btn-remove-field"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </div>
                        <div class="flex gap-2 items-center field-row">
                            <input type="text" name="keys[]" value="Bahan Galian" class="w-1/3 bg-gray-50 border border-gray-300 text-xs rounded p-2 dark:bg-gray-700 dark:text-white" placeholder="Nama Field">
                            <input type="text" name="values[]" class="w-full bg-gray-50 border border-gray-300 text-xs rounded p-2 dark:bg-gray-700 dark:text-white" placeholder="Isi Data">
                            <button type="button" class="text-red-500 hover:text-red-700 btn-remove-field"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" id="btn-submit" class="w-full text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan Data</button>
                    </div>
                </form>
            </div>

            <!-- List Koordinat Preview -->
            <div class="mt-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <h4 class="text-xs font-bold uppercase text-gray-500 mb-2">Titik Koordinat Terpilih</h4>
                <ul id="coord-list" class="text-xs font-mono text-gray-600 dark:text-gray-400 space-y-1 h-32 overflow-y-auto">
                    <li class="italic text-gray-400">Belum ada titik.</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. INISIALISASI PETA (Default: Indonesia)
            const map = L.map('map').setView([-0.7893, 113.9213], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            let markers = [];
            let polygon = null;
            let latlngs = [];

            // 2. KLIK PETA -> TAMBAH TITIK
            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                // Tambah ke array data
                latlngs.push([lat, lng]);

                // Tambah Marker Visual
                const marker = L.marker([lat, lng]).addTo(map);
                markers.push(marker);

                // Gambar/Update Poligon
                if (polygon) {
                    map.removeLayer(polygon);
                }
                if (latlngs.length > 1) {
                    polygon = L.polygon(latlngs, {color: 'red'}).addTo(map);
                }

                // Update List & Input
                updateCoordInput();
            });

            // 3. FUNGSI UPDATE INPUT & LIST
            function updateCoordInput() {
                // Update Hidden Input untuk dikirim ke Server
                document.getElementById('coordinates_input').value = JSON.stringify(latlngs);

                // Update List Preview (Kanan Bawah)
                const list = document.getElementById('coord-list');
                list.innerHTML = '';
                latlngs.forEach((coord, index) => {
                    list.innerHTML += `<li>${index + 1}. Lat: ${coord[0].toFixed(5)}, Lng: ${coord[1].toFixed(5)}</li>`;
                });
            }

            // 4. RESET PETA
            document.getElementById('btn-clear-map').addEventListener('click', function() {
                markers.forEach(m => map.removeLayer(m));
                if (polygon) map.removeLayer(polygon);
                markers = [];
                polygon = null;
                latlngs = [];
                updateCoordInput();
            });

            // 5. FORM DINAMIS (TAMBAH FIELD)
            const container = document.getElementById('dynamic-fields-container');

            document.getElementById('btn-add-field').addEventListener('click', function() {
                const div = document.createElement('div');
                div.className = 'flex gap-2 items-center field-row';
                div.innerHTML = `
                    <input type="text" name="keys[]" class="w-1/3 bg-gray-50 border border-gray-300 text-xs rounded p-2 dark:bg-gray-700 dark:text-white" placeholder="Label">
                    <input type="text" name="values[]" class="w-full bg-gray-50 border border-gray-300 text-xs rounded p-2 dark:bg-gray-700 dark:text-white" placeholder="Isi">
                    <button type="button" class="text-red-500 hover:text-red-700 btn-remove-field"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                `;
                container.appendChild(div);
            });

            // Hapus Field (Event Delegation)
            container.addEventListener('click', function(e) {
                if (e.target.closest('.btn-remove-field')) {
                    e.target.closest('.field-row').remove();
                }
            });

            // Validasi sebelum submit
            document.getElementById('geoForm').addEventListener('submit', function(e) {
                if (latlngs.length < 3) {
                    e.preventDefault();
                    alert('Mohon buat minimal 3 titik di peta untuk membentuk area (poligon).');
                }
            });
        });
    </script>
</x-app-layout>
