<x-app-layout>
    <!-- Leaflet Assets -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Turf.js untuk Kalkulasi Geospasial -->
    <script src="https://unpkg.com/@turf/turf/turf.min.js"></script>

    <div class="flex h-[calc(100vh-80px)] relative overflow-hidden bg-gray-100 dark:bg-gray-900">

        <!-- PETA (KIRI) -->
        <div id="map" class="flex-1 z-0 h-full w-full"></div>

        <!-- SIDEBAR (KANAN) -->
        <div class="w-full md:w-[400px] bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 flex flex-col shadow-2xl z-20 h-full transition-all duration-300 absolute right-0 top-0 md:relative transform translate-x-full md:translate-x-0" id="sidebar-panel">

            <!-- 1. HEADER & SEARCH -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shrink-0 z-30">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="font-bold text-gray-800 dark:text-white text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 7m0 13V7"></path></svg>
                        Data Lokasi
                    </h2>
                    <div class="flex gap-2">
                        <button id="btn-add-new" class="text-xs bg-primary-600 text-white px-3 py-1.5 rounded-lg hover:bg-primary-700 transition shadow-sm flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Baru
                        </button>
                        <button id="btn-cancel-action" class="hidden text-xs bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-300">
                            Tutup
                        </button>
                    </div>
                </div>

                <div id="search-container" class="relative">
                    <input type="text" id="search-input" class="block w-full p-2.5 ps-9 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Cari lokasi...">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/></svg>
                    </div>
                </div>
            </div>

            <!-- 2. PANEL DETAIL (View Mode) -->
            <div id="detail-view" class="hidden bg-blue-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 flex-col shrink-0 max-h-[60vh] overflow-y-auto custom-scrollbar transition-all">
                <div class="p-4 relative">
                    <button onclick="closeDetail()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 bg-white dark:bg-gray-800 rounded-full p-1 shadow-sm border border-gray-200 dark:border-gray-600 z-10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1 pr-6" id="detail-title">Nama Lokasi</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4" id="detail-desc">Deskripsi...</p>

                    <div class="flex gap-2 mb-4">
                         <div class="flex-1 bg-green-100 dark:bg-green-900/30 p-2 rounded border border-green-200 dark:border-green-800 text-center">
                            <p class="text-[10px] uppercase font-bold text-green-600 dark:text-green-400">Luas Area</p>
                            <p class="text-lg font-bold text-green-800 dark:text-green-300" id="detail-area">- Ha</p>
                        </div>
                         <div class="flex-1 bg-blue-100 dark:bg-blue-900/30 p-2 rounded border border-blue-200 dark:border-blue-800 text-center">
                            <p class="text-[10px] uppercase font-bold text-blue-600 dark:text-blue-400">Jumlah Titik</p>
                            <p class="text-lg font-bold text-blue-800 dark:text-blue-300" id="detail-points-count">0</p>
                        </div>
                    </div>

                    <div id="detail-owner-info" class="hidden mb-4 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-1">Dibuat Oleh</p>
                        <div class="text-sm font-medium text-gray-900 dark:text-white" id="detail-owner-name">-</div>
                        <div class="text-xs text-gray-500" id="detail-owner-email">-</div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3 mb-4 shadow-sm">
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Data Properti</h4>
                        <div id="detail-properties" class="space-y-1 text-sm"></div>
                    </div>

                    <div class="flex gap-2">
                        <button id="btn-edit-detail" class="flex-1 bg-yellow-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-yellow-600 transition shadow-sm">Edit Data</button>

                        <!-- TOMBOL HAPUS YANG DIPERBAIKI (Trigger Modal) -->
                        <button id="btn-delete-detail" type="button" class="flex-1 bg-red-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition shadow-sm">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>

            <!-- 3. LIST DATA -->
            <div id="list-view" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50 dark:bg-gray-900/50 custom-scrollbar">
                @forelse($data as $geo)
                    <div class="geo-item bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all cursor-pointer group relative"
                         onclick="selectLocation({{ $geo->id }})"
                         data-title="{{ strtolower($geo->title) }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-gray-800 dark:text-white text-sm group-hover:text-primary-600 transition-colors">{{ $geo->title }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 mt-0.5">{{ $geo->description ?? 'Tanpa deskripsi' }}</p>
                            </div>
                            <div class="flex items-center gap-1">
                                <button onclick="event.stopPropagation(); confirmDeleteData('{{ $geo->id }}', '{{ $geo->title }}')" class="text-gray-300 hover:text-red-500 p-1 rounded-full hover:bg-red-50 dark:hover:bg-red-900/30 transition" title="Hapus Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                <button onclick="event.stopPropagation(); toggleVisibility({{ $geo->id }}, this)" class="text-gray-300 hover:text-blue-500 p-1 rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/30 transition">
                                    <svg class="w-4 h-4 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg class="w-4 h-4 eye-closed hidden text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>
                        @if($geo->properties && isset($geo->properties['Luas Lahan']))
                        <div class="mt-2">
                             <span class="text-[10px] bg-green-50 text-green-600 border border-green-200 px-2 py-0.5 rounded font-bold">{{ $geo->properties['Luas Lahan'] }} Ha</span>
                        </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400 text-sm flex flex-col items-center">
                        <svg class="w-10 h-10 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 7m0 13V7"></path></svg>
                        Belum ada data pemetaan.
                    </div>
                @endforelse
            </div>

            <!-- 4. FORM VIEW (Input/Edit Mode) -->
            <div id="form-view" class="hidden flex-1 overflow-y-auto p-5 bg-white dark:bg-gray-800 custom-scrollbar h-full z-40 absolute top-[60px] bottom-0 w-full border-t border-gray-200 dark:border-gray-700">
                <form id="geoForm" method="POST" action="{{ route('geoportal.store') }}">
                    @csrf
                    <div id="method-spoofing"></div>
                    <input type="hidden" name="coordinates" id="coordinates_input">

                    <div class="mb-4">
                        <label class="block mb-1 text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Nama Lokasi</label>
                        <input type="text" name="title" id="input_title" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm" placeholder="Contoh: Blok Tambang A" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Deskripsi</label>
                        <textarea name="description" id="input_description" rows="2" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm"></textarea>
                    </div>

                    <!-- FIELD LUAS LAHAN (FIXED) -->
                    <div class="mb-4 bg-green-50 dark:bg-green-900/20 p-3 rounded-lg border border-green-200 dark:border-green-800">
                        <label class="block mb-1 text-xs font-bold text-green-700 dark:text-green-400 uppercase">Estimasi Luas Lahan</label>
                        <div class="relative">
                            <input type="text" name="fixed_luas" id="input_luas" class="w-full text-sm font-bold text-green-800 border-green-300 rounded-lg bg-white focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-green-600 dark:text-green-400 cursor-not-allowed" readonly placeholder="Gambar di peta untuk hitung..." required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none"><span class="text-xs text-green-600 font-bold">Ha</span></div>
                        </div>
                    </div>

                    <!-- Koordinat (REVISED UI & LOGIC) -->
                    <div class="mb-5 border border-gray-200 dark:border-gray-700 rounded-lg p-3 bg-gray-50 dark:bg-gray-900/50">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold uppercase text-gray-500">Titik Koordinat</label>
                            <button type="button" onclick="addNewPoint()" class="text-[10px] bg-white border border-gray-300 text-gray-700 px-2 py-1 rounded shadow-sm hover:bg-gray-50">+ Manual</button>
                        </div>

                        <!-- Header Kolom -->
                        <div class="flex gap-1 text-[10px] font-bold text-gray-400 uppercase mb-1 px-1">
                            <span class="w-6 text-center">#</span>
                            <span class="w-1/2">Garis Lintang (LS)</span>
                            <span class="w-1/2">Garis Bujur (BT)</span>
                            <span class="w-6"></span>
                        </div>

                        <div id="coords-container" class="space-y-1 max-h-48 overflow-y-auto pr-1 custom-scrollbar"></div>
                    </div>

                    <!-- Fluid Properties -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold uppercase text-gray-500">Data Tambahan</label>
                            <button type="button" onclick="addPropertyField()" class="text-[10px] bg-blue-50 text-blue-600 border border-blue-200 px-2 py-1 rounded hover:bg-blue-100 font-bold">+ Field Baru</button>
                        </div>
                        <div id="properties-container" class="space-y-2"></div>
                    </div>

                    <button type="submit" class="w-full bg-primary-600 text-white py-3 rounded-lg text-sm font-bold hover:bg-primary-700 transition shadow-lg transform hover:-translate-y-0.5">Simpan Data</button>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL KONFIRMASI HAPUS TITIK -->
    <div id="confirmDeletePointModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[60] justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-sm bg-gray-900/50">
        <div class="relative p-4 w-full max-w-xs max-h-full">
            <div class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700 transform transition-all scale-100">
                <div class="p-5 text-center">
                    <div class="mx-auto mb-4 text-red-500 bg-red-100 dark:bg-red-900/30 w-12 h-12 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </div>
                    <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-white">Hapus Titik?</h3>
                    <div class="flex justify-center gap-3">
                        <button onclick="closeDeletePointModal()" type="button" class="text-gray-700 bg-white hover:bg-gray-100 border border-gray-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">Batal</button>
                        <button id="btn-confirm-delete-point" type="button" class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-4 py-2 shadow-lg">Ya</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL KONFIRMASI HAPUS DATA LOKASI (NEW) -->
    <div id="deleteDataModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[60] justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-sm bg-gray-900/50">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <div class="p-5 text-center">
                    <div class="mx-auto mb-4 text-red-500 bg-red-100 dark:bg-red-900/30 w-14 h-14 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                    <h3 class="mb-1 text-xl font-bold text-gray-900 dark:text-white">Hapus Data Lokasi?</h3>
                    <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">Anda akan menghapus permanen: <br> <b id="delete_item_title" class="text-gray-800 dark:text-white"></b></p>

                    <form id="deleteDataForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-center gap-3">
                            <button onclick="document.getElementById('deleteDataModal').classList.add('hidden')" type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                Batal
                            </button>
                            <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center shadow-lg">
                                Ya, Hapus Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569; }
        .dist-label { background: rgba(255, 255, 255, 0.9); border: 1px solid #333; color: #333; padding: 1px 4px; border-radius: 4px; font-size: 10px; font-weight: bold; white-space: nowrap; box-shadow: 1px 1px 2px rgba(0,0,0,0.2); }
    </style>

    <!-- LOGIKA APLIKASI -->
    <script>
        const rawData = {!! json_encode($data) !!};
        let map, drawLayer, savedLayer, measurementLayer;
        let points = [];
        let deletePointIndex = null;

        document.addEventListener('DOMContentLoaded', function() {
            // INIT MAP
            map = L.map('map', { zoomControl: false }).setView([-2.5, 118], 5);
            L.control.zoom({ position: 'topleft' }).addTo(map);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19, attribution: '© OpenStreetMap'
            }).addTo(map);

            savedLayer = L.layerGroup().addTo(map);
            drawLayer = L.layerGroup().addTo(map);
            measurementLayer = L.layerGroup().addTo(map);

            renderMapData();

            // MAP CLICK
            map.on('click', function(e) {
                if(!document.getElementById('form-view').classList.contains('hidden')) {
                    addPoint(e.latlng.lat, e.latlng.lng);
                } else {
                    // View mode click logic if needed
                }
            });

            // SEARCH
            document.getElementById('search-input').addEventListener('keyup', function() {
                const val = this.value.toLowerCase();
                document.querySelectorAll('.geo-item').forEach(item => {
                    const title = item.getAttribute('data-title');
                    item.style.display = title.includes(val) ? 'block' : 'none';
                });
            });

            document.getElementById('btn-add-new').addEventListener('click', () => openForm('create'));
            document.getElementById('btn-cancel-action').addEventListener('click', () => {
                closeForm();
                closeDetail();
            });

            // Confirm Delete Point
            document.getElementById('btn-confirm-delete-point').addEventListener('click', function() {
                if(deletePointIndex !== null) {
                    points.splice(deletePointIndex, 1);
                    redrawDrawing();
                    renderInputs();
                    calculateArea();
                    if(window.showToast) showToast('Titik koordinat berhasil dihapus.', 'success');
                    closeDeletePointModal();
                }
            });
        });

        function renderMapData() {
            savedLayer.clearLayers();
            rawData.forEach(geo => {
                if(geo.coordinates && geo.coordinates.length > 0) {
                    const poly = L.polygon(geo.coordinates, {
                        color: '#3b82f6', fillColor: '#3b82f6', fillOpacity: 0.3, weight: 2
                    });
                    poly.on('click', (e) => {
                        L.DomEvent.stopPropagation(e);
                        selectLocation(geo.id);
                    });
                    poly.geoId = geo.id;
                    savedLayer.addLayer(poly);
                }
            });
        }

        window.selectLocation = function(id) {
            const data = rawData.find(x => x.id == id);
            if(!data) return;

            document.getElementById('detail-title').innerText = data.title;
            document.getElementById('detail-desc').innerText = data.description || '-';

            const ownerInfo = document.getElementById('detail-owner-info');
            if(data.user && ownerInfo) {
                ownerInfo.classList.remove('hidden');
                document.getElementById('detail-owner-name').innerText = data.user.full_name;
                document.getElementById('detail-owner-email').innerText = `${data.user.email} (${data.user.username})`;
            }

            document.getElementById('detail-points-count').innerText = data.coordinates ? data.coordinates.length : 0;
            document.getElementById('detail-area').innerText = (data.properties && data.properties['Luas Lahan']) ? data.properties['Luas Lahan'] + ' Ha' : '-';

            const propsContainer = document.getElementById('detail-properties');
            propsContainer.innerHTML = '';
            if(data.properties) {
                Object.entries(data.properties).forEach(([k, v]) => {
                    if (k !== 'Luas Lahan') {
                        propsContainer.innerHTML += `
                            <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-1 last:border-0">
                                <span class="text-gray-500 dark:text-gray-400">${k}</span>
                                <span class="font-medium text-gray-900 dark:text-white text-right">${v}</span>
                            </div>`;
                    }
                });
            }

            document.getElementById('btn-edit-detail').onclick = () => openForm('edit', id);
            // Setup Delete Modal Trigger for Detail View
            document.getElementById('btn-delete-detail').onclick = () => confirmDeleteData(id, data.title);

            document.getElementById('detail-view').classList.remove('hidden');

            measurementLayer.clearLayers();
            savedLayer.eachLayer(l => {
                if(l.geoId == id) {
                    l.setStyle({ color: '#f59e0b', fillColor: '#f59e0b', weight: 3 });
                    map.fitBounds(l.getBounds(), { padding: [50, 50] });
                } else {
                    l.setStyle({ color: '#3b82f6', fillColor: '#3b82f6', weight: 2 });
                }
            });

            if(data.coordinates && data.coordinates.length > 0) {
                const coords = data.coordinates;
                coords.forEach((p, i) => {
                    const marker = L.circleMarker(p, {
                        radius: 6, color: '#fff', weight: 2, fillColor: '#f59e0b', fillOpacity: 1
                    }).addTo(measurementLayer);
                    marker.bindPopup(`<div class="text-center"><b class="text-sm">Titik ${i+1}</b><br><span class="text-xs text-gray-500">${p[0].toFixed(5)}, ${p[1].toFixed(5)}</span></div>`);
                    marker.bindTooltip(`P${i+1}`, {permanent: true, direction: 'top', className: 'bg-transparent border-none font-bold text-amber-600 shadow-none'});
                });

                if (coords.length > 1) {
                    for (let i = 0; i < coords.length; i++) {
                        const p1 = coords[i];
                        const p2 = coords[(i + 1) % coords.length];
                        const from = turf.point([p1[1], p1[0]]);
                        const to = turf.point([p2[1], p2[0]]);
                        const distKm = turf.distance(from, to);
                        let distText = distKm < 1 ? (distKm * 1000).toFixed(0) + ' m' : distKm.toFixed(2) + ' km';
                        const midLat = (p1[0] + p2[0]) / 2;
                        const midLng = (p1[1] + p2[1]) / 2;
                        const labelIcon = L.divIcon({
                            className: 'dist-label-container',
                            html: `<div class="dist-label">${distText}</div>`,
                            iconSize: [50, 20],
                            iconAnchor: [25, 10]
                        });
                        L.marker([midLat, midLng], {icon: labelIcon, interactive: false}).addTo(measurementLayer);
                    }
                }
            }
        };

        window.closeDetail = function() {
            document.getElementById('detail-view').classList.add('hidden');
            measurementLayer.clearLayers();
            savedLayer.eachLayer(l => l.setStyle({ color: '#3b82f6', fillColor: '#3b82f6', weight: 2 }));
        };

        // --- FORM & DATA DELETION ---

        // New function to handle Data Deletion via Modal
        window.confirmDeleteData = function(id, title) {
            document.getElementById('delete_item_title').innerText = title;
            document.getElementById('deleteDataForm').action = `/geoportal/${id}`;

            const modal = document.getElementById('deleteDataModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        };

        window.openForm = function(mode, id = null) {
            // ... UI Switch logic ...
            document.getElementById('list-view').classList.add('hidden');
            document.getElementById('detail-view').classList.add('hidden');
            document.getElementById('form-view').classList.remove('hidden');
            document.getElementById('btn-add-new').classList.add('hidden');
            document.getElementById('btn-cancel-action').classList.remove('hidden');
            document.getElementById('search-container').classList.add('hidden');

            points = [];
            drawLayer.clearLayers();
            measurementLayer.clearLayers();
            document.getElementById('coords-container').innerHTML = '';
            document.getElementById('properties-container').innerHTML = '';
            document.getElementById('input_luas').value = '';

            const form = document.getElementById('geoForm');
            const methodSpoof = document.getElementById('method-spoofing');

            if (mode === 'create') {
                form.action = "{{ route('geoportal.store') }}";
                methodSpoof.innerHTML = '';
                document.getElementById('input_title').value = '';
                document.getElementById('input_description').value = '';
                addPropertyField('Komoditas', '');
            } else if (mode === 'edit' && id) {
                const data = rawData.find(x => x.id == id);
                form.action = `/geoportal/${id}`;
                methodSpoof.innerHTML = '<input type="hidden" name="_method" value="PUT">';

                document.getElementById('input_title').value = data.title;
                document.getElementById('input_description').value = data.description;

                if(data.coordinates) {
                    points = data.coordinates;
                    redrawDrawing();
                    renderInputs();
                    calculateArea();
                    if(points.length > 0) map.fitBounds(L.polygon(points).getBounds());
                }

                if(data.properties) {
                    if(data.properties['Luas Lahan']) document.getElementById('input_luas').value = data.properties['Luas Lahan'];
                    Object.entries(data.properties).forEach(([k,v]) => {
                        if(k !== 'Luas Lahan') addPropertyField(k,v);
                    });
                }
            }
        };

        window.closeForm = function() {
            document.getElementById('form-view').classList.add('hidden');
            document.getElementById('list-view').classList.remove('hidden');
            document.getElementById('btn-add-new').classList.remove('hidden');
            document.getElementById('btn-cancel-action').classList.add('hidden');
            document.getElementById('search-container').classList.remove('hidden');
            drawLayer.clearLayers();
        };

        // --- DRAWING LOGIC FIX ---

        function addPoint(lat, lng) {
            points.push([lat, lng]);
            redrawDrawing();
            renderInputs();
            calculateArea();
        }

        // FIX: Update Coordinate by Index & Type (Avoids overwriting other value)
        window.updateCoordinate = function(index, type, value) {
            let val = parseFloat(value);
            if(isNaN(val)) return;

            if (type === 'lat') {
                points[index][0] = val;
            } else if (type === 'lng') {
                points[index][1] = val;
            }

            redrawDrawing(false); // redraw without refreshing inputs to keep focus
            calculateArea();
        }

        function confirmRemovePoint(index) {
            deletePointIndex = index;
            const modal = document.getElementById('confirmDeletePointModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeletePointModal() {
            const modal = document.getElementById('confirmDeletePointModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            deletePointIndex = null;
        }

        function redrawDrawing(refreshInputs = true) {
            drawLayer.clearLayers();
            points.forEach((p, i) => {
                L.circleMarker(p, {color: 'red', radius: 4}).addTo(drawLayer)
                 .bindTooltip(`P${i+1}`, {permanent: true, direction: 'right', className: 'bg-white px-1 rounded border shadow-sm text-[10px] font-bold'});
            });
            if (points.length > 1) {
                L.polygon(points, {color: 'red', dashArray: '5, 5', weight: 2, fillOpacity: 0.1}).addTo(drawLayer);
            }
            document.getElementById('coordinates_input').value = JSON.stringify(points);

            // Only refresh inputs if requested (to avoid losing focus while typing)
            if(refreshInputs) renderInputs();
        }

        function calculateArea() {
            const luasInput = document.getElementById('input_luas');
            if (points.length < 3) {
                luasInput.value = '';
                return;
            }
            try {
                const turfPoints = points.map(p => [p[1], p[0]]);
                turfPoints.push(turfPoints[0]);
                const polygon = turf.polygon([turfPoints]);
                const areaHectares = turf.area(polygon) / 10000;
                luasInput.value = areaHectares.toFixed(4);
            } catch (e) {
                console.error("Error luas:", e);
            }
        }

        function renderInputs() {
            const container = document.getElementById('coords-container');
            container.innerHTML = '';
            points.forEach((p, i) => {
                container.innerHTML += `
                    <div class="flex gap-1 items-center bg-white dark:bg-gray-700 p-2 rounded border border-gray-200 dark:border-gray-600 text-xs mb-1 shadow-sm">
                        <div class="w-6 text-center font-bold text-gray-400 border-r border-gray-100 dark:border-gray-600 mr-1">P${i+1}</div>

                        <div class="flex-1">
                            <label class="block text-[9px] text-gray-400 mb-0.5">Lintang (LS)</label>
                            <input type="number" step="any" value="${p[0]}"
                                   oninput="updateCoordinate(${i}, 'lat', this.value)"
                                   class="w-full border border-gray-200 rounded px-1 py-0.5 text-xs bg-gray-50 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>

                        <div class="flex-1">
                            <label class="block text-[9px] text-gray-400 mb-0.5">Bujur (BT)</label>
                            <input type="number" step="any" value="${p[1]}"
                                   oninput="updateCoordinate(${i}, 'lng', this.value)"
                                   class="w-full border border-gray-200 rounded px-1 py-0.5 text-xs bg-gray-50 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>

                        <button type="button" onclick="confirmRemovePoint(${i})" class="ml-1 text-red-400 hover:text-red-600 hover:bg-red-50 p-1 rounded transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>`;
            });
            container.scrollTop = container.scrollHeight;
        }

        window.addNewPoint = function() {
            const center = map.getCenter();
            addPoint(center.lat, center.lng);
        };

        window.addPropertyField = function(key = '', value = '') {
            const container = document.getElementById('properties-container');
            const div = document.createElement('div');
            div.className = 'flex gap-1 property-row mb-1';
            div.innerHTML = `
                <input type="text" name="keys[]" value="${key}" class="w-1/3 text-xs border-gray-300 rounded h-8" placeholder="Label">
                <input type="text" name="values[]" value="${value}" class="w-full text-xs border-gray-300 rounded h-8" placeholder="Isi">
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:bg-red-100 p-1 rounded"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            `;
            container.appendChild(div);
        };

        window.toggleVisibility = function(id, btn) {
            const openIcon = btn.querySelector('.eye-open');
            const closedIcon = btn.querySelector('.eye-closed');

            let targetLayer;
            savedLayer.eachLayer(l => { if(l.geoId == id) targetLayer = l; });

            if(targetLayer) {
                savedLayer.removeLayer(targetLayer);
                if(!window.hiddenCache) window.hiddenCache = {};
                window.hiddenCache[id] = targetLayer;
                openIcon.classList.add('hidden'); closedIcon.classList.remove('hidden'); btn.closest('.geo-item').classList.add('opacity-50');
            } else if(window.hiddenCache && window.hiddenCache[id]) {
                savedLayer.addLayer(window.hiddenCache[id]);
                delete window.hiddenCache[id];
                openIcon.classList.remove('hidden'); closedIcon.classList.add('hidden'); btn.closest('.geo-item').classList.remove('opacity-50');
            }
        };
    </script>
</x-app-layout>
