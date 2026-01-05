<x-app-layout>
    <!-- Leaflet Assets -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Turf.js untuk Kalkulasi Geospasial -->
    <script src="https://unpkg.com/@turf/turf/turf.min.js"></script>

    <div class="flex h-[calc(100vh-64px)] relative overflow-hidden bg-gray-50/50">
        <!-- MAP AREA -->
        <div id="map" class="flex-1 z-0 h-full w-full bg-slate-200"></div>

        <!-- SIDEBAR PANEL -->
        <div class="w-full md:w-[400px] bg-white border-l border-gray-100 flex flex-col shadow-2xl z-20 h-full transition-all duration-300 absolute right-0 top-0 md:relative transform translate-x-full md:translate-x-0" id="sidebar-panel">
            
            <!-- SEARCH & ADD HEADER -->
            <div class="p-6 border-b border-gray-50 bg-white shrink-0">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-widest flex items-center gap-3">
                            <div class="p-2 bg-primary-50 rounded-lg">
                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 7m0 13V7"></path></svg>
                            </div>
                            Geoportal
                        </h2>
                    </div>
                    <div class="flex gap-2">
                        <button id="btn-add-new" class="text-[10px] font-bold uppercase tracking-widest bg-primary-600 text-white px-4 py-2.5 rounded-xl hover:bg-primary-700 transition shadow-lg shadow-primary-100 flex items-center gap-2 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Baru
                        </button>
                        <button id="btn-cancel-action" class="hidden text-[10px] font-bold uppercase tracking-widest bg-gray-100 text-gray-500 px-4 py-2.5 rounded-xl hover:bg-gray-200 transition active:scale-95">
                            Tutup
                        </button>
                    </div>
                </div>

                <div id="search-container" class="relative group">
                    <input type="text" id="search-input" class="w-full pl-10 pr-4 py-3 bg-gray-50 border-transparent focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-50/50 rounded-2xl transition-all text-xs font-medium placeholder-gray-400 shadow-inner" placeholder="Cari lokasi atau area...">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- DETAIL VIEW -->
            <div id="detail-view" class="hidden flex-col shrink-0 max-h-[60vh] overflow-y-auto custom-scrollbar border-b border-gray-50 bg-primary-50/10">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1 min-w-0 pr-8">
                            <h3 class="text-lg font-extrabold text-gray-900 tracking-tight" id="detail-title">Nama Lokasi</h3>
                            <p class="text-xs text-gray-500 font-medium mt-1 leading-relaxed" id="detail-desc">Deskripsi...</p>
                        </div>
                        <button onclick="closeDetail()" class="p-2 text-gray-400 hover:text-gray-900 hover:bg-white rounded-xl transition-all shadow-sm border border-transparent hover:border-gray-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm transition-transform hover:scale-[1.02]">
                            <p class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest mb-1.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Luas Area
                            </p>
                            <p class="text-lg font-black text-gray-900" id="detail-area">- Ha</p>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm transition-transform hover:scale-[1.02]">
                            <p class="text-[9px] font-bold text-blue-600 uppercase tracking-widest mb-1.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                                Titik Koordinat
                            </p>
                            <p class="text-lg font-black text-gray-900" id="detail-points-count">0</p>
                        </div>
                    </div>

                    <div id="detail-owner-info" class="hidden mb-6 p-4 bg-gray-50/80 backdrop-blur-sm rounded-2xl border border-gray-100">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Penanggung Jawab</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-primary-600 font-bold uppercase shadow-sm" id="detail-owner-initials">US</div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-gray-900 truncate" id="detail-owner-name">-</p>
                                <p class="text-[10px] text-gray-500 truncate" id="detail-owner-email">-</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 p-5 mb-6 shadow-sm">
                        <h4 class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-4 border-b border-gray-50 pb-2">Atribut Geospasial</h4>
                        <div id="detail-properties" class="space-y-3"></div>
                    </div>

                    <div class="flex gap-3">
                        <button id="btn-edit-detail" class="flex-1 bg-white border border-gray-200 text-gray-700 py-3 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm active:scale-95">Edit Data</button>
                        <button id="btn-delete-detail" type="button" class="flex-1 bg-red-50 text-red-600 border border-red-100 py-3 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-red-100 transition-all active:scale-95">Hapus Area</button>
                    </div>
                </div>
            </div>

            <!-- LIST VIEW -->
            <div id="list-view" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50/50 custom-scrollbar">
                @forelse($data as $geo)
                    <div class="geo-item group bg-white p-4 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-primary-600/5 hover:-translate-y-0.5 transition-all cursor-pointer relative"
                         onclick="selectLocation({{ $geo->id }})"
                         data-title="{{ strtolower($geo->title) }}">
                        <div class="flex justify-between items-start gap-4">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="font-bold text-gray-900 text-[13px] truncate group-hover:text-primary-600 transition-colors">{{ $geo->title }}</h4>
                                    @if($geo->properties && isset($geo->properties['Luas Lahan']))
                                        <span class="text-[9px] bg-emerald-50 text-emerald-600 font-bold px-1.5 py-0.5 rounded-md border border-emerald-100 whitespace-nowrap">{{ $geo->properties['Luas Lahan'] }} Ha</span>
                                    @endif
                                </div>
                                <p class="text-[10px] text-gray-400 font-medium line-clamp-1 leading-relaxed">{{ $geo->description ?? 'Tidak ada keterangan tambahan' }}</p>
                            </div>
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="event.stopPropagation(); confirmDeleteData('{{ $geo->id }}', '{{ $geo->title }}')" class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                <button onclick="event.stopPropagation(); toggleVisibility({{ $geo->id }}, this)" class="p-2 text-gray-300 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition">
                                    <svg class="w-4 h-4 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg class="w-4 h-4 eye-closed hidden text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 border border-white shadow-inner">
                            <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 7m0 13V7"></path></svg>
                        </div>
                        <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-2">Peta Masih Kosong</h4>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-loose px-12">Mulai tambahkan area pemetaan pertama Anda</p>
                    </div>
                @endforelse
            </div>

            <!-- FORM VIEW -->
            <div id="form-view" class="hidden flex-1 overflow-y-auto p-6 bg-white shrink-0 scroll-smooth custom-scrollbar">
                <form id="geoForm" method="POST" action="{{ route('geoportal.store') }}" class="space-y-6">
                    @csrf
                    <div id="method-spoofing"></div>
                    <input type="hidden" name="coordinates" id="coordinates_input">

                    <div class="space-y-4">
                        <div>
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Informasi Dasar Area</label>
                            <input type="text" name="title" id="input_title" class="w-full text-sm font-bold bg-gray-50 border-transparent focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-50/50 rounded-2xl transition-all p-4 shadow-inner" placeholder="Pemberian Nama Area" required>
                        </div>

                        <div>
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Deskripsi Kualitatif</label>
                            <textarea name="description" id="input_description" rows="3" class="w-full text-sm font-medium bg-gray-50 border-transparent focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-50/50 rounded-2xl transition-all p-4 shadow-inner" placeholder="Tuliskan detail atau catatan area ini..."></textarea>
                        </div>
                    </div>

                    <div class="p-5 bg-emerald-50 rounded-3xl border border-emerald-100/50 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-10 scale-150 rotate-12 group-hover:rotate-45 transition-transform">
                            <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <label class="block mb-2 text-[10px] font-black text-emerald-700 uppercase tracking-widest">Estimasi Luas Lahan</label>
                        <div class="relative flex items-center">
                            <input type="text" name="fixed_luas" id="input_luas" class="w-full text-2xl font-black text-emerald-900 bg-transparent border-none focus:ring-0 p-0 placeholder-emerald-200" readonly placeholder="Auto-Calculated" required>
                            <span class="text-xs font-black text-emerald-600 uppercase">HEKTARE (Ha)</span>
                        </div>
                        <p class="text-[9px] text-emerald-600/60 font-medium mt-2">Dihitung otomatis saat Anda menggambar di peta</p>
                    </div>

                    <div class="bg-gray-50 rounded-3xl border border-gray-100 p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Daftar Koordinat</label>
                            <button type="button" onclick="addNewPoint()" class="px-3 py-1.5 bg-white border border-gray-100 text-[9px] font-bold text-gray-600 uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all flex items-center gap-1.5 active:scale-95">
                                <svg class="w-3 h-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah Manual
                            </button>
                        </div>

                        <div id="coords-container" class="space-y-2 max-h-60 overflow-y-auto pr-2 custom-scrollbar"></div>
                        <p class="text-[9px] text-gray-400 font-medium text-center italic">Hubungkan minimal 3 titik untuk membentuk area</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Atribut Tambahan</label>
                            <button type="button" onclick="addPropertyField()" class="px-3 py-1.5 bg-primary-50 text-primary-600 text-[9px] font-bold uppercase tracking-widest rounded-lg border border-primary-100 hover:bg-primary-100 transition-all active:scale-95">
                                + Atribut Baru
                            </button>
                        </div>
                        <div id="properties-container" class="space-y-3"></div>
                    </div>

                    <div class="pt-4 sticky bottom-0 bg-white">
                        <button type="submit" class="w-full bg-primary-600 text-white py-5 rounded-[2rem] text-[11px] font-black uppercase tracking-widest hover:bg-primary-700 transition-all shadow-xl shadow-primary-200 active:scale-[0.98] transform hover:-translate-y-1">Simpan Pemetaan Area</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL KONFIRMASI HAPUS TITIK -->
    <div id="confirmDeletePointModal" tabindex="-1" class="hidden fixed inset-0 z-[60] overflow-y-auto flex items-center justify-center p-4 backdrop-blur-sm bg-gray-900/10">
        <div class="relative w-full max-w-xs">
            <div class="bg-white rounded-3xl shadow-2xl p-6 text-center border border-white">
                <div class="mx-auto mb-4 text-red-500 bg-red-50 w-12 h-12 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-2">Hapus Titik?</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-6">Tindakan ini tidak bisa dibatalkan</p>
                <div class="flex justify-center gap-3">
                    <button onclick="closeDeletePointModal()" class="flex-1 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest hover:text-gray-900 transition-colors">Batal</button>
                    <button id="btn-confirm-delete-point" class="flex-1 py-3 bg-red-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl hover:bg-red-700 transition-all shadow-lg shadow-red-200">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL KONFIRMASI HAPUS DATA LOKASI -->
    <div id="deleteDataModal" tabindex="-1" class="hidden fixed inset-0 z-[60] overflow-y-auto flex items-center justify-center p-4 backdrop-blur-sm bg-gray-900/10">
        <div class="relative w-full max-w-md">
            <div class="bg-white rounded-3xl shadow-2xl p-8 text-center border border-white">
                <div class="mx-auto mb-6 text-red-500 bg-red-50 w-16 h-16 rounded-[2rem] flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h3 class="text-lg font-black text-gray-900 uppercase tracking-widest mb-2">Hapus Data Lokasi?</h3>
                <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest leading-loose mb-8">
                    Anda akan menghapus permanen area:<br>
                    <span id="delete_item_title" class="text-gray-900 font-black"></span>
                </p>

                <form id="deleteDataForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-center gap-4">
                        <button onclick="document.getElementById('deleteDataModal').classList.add('hidden')" type="button" class="flex-1 py-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest hover:text-gray-900 transition-colors">Batal</button>
                        <button type="submit" class="flex-1 py-4 bg-red-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-[1.5rem] hover:bg-red-700 transition-all shadow-xl shadow-red-200">Ya, Hapus Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #f1f5f9; border-radius: 20px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background-color: #e2e8f0; }
        .dist-label { background: white; border: 1px solid #e2e8f0; color: #1e293b; padding: 2px 6px; border-radius: 6px; font-size: 9px; font-weight: 800; white-space: nowrap; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); text-transform: uppercase; letter-spacing: 0.05em; }
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
                    closeDeletePointModal();
                }
            });
        });

        function renderMapData() {
            savedLayer.clearLayers();
            rawData.forEach(geo => {
                if(geo.coordinates && geo.coordinates.length > 0) {
                    const poly = L.polygon(geo.coordinates, {
                        color: '#4f46e5', fillColor: '#4f46e5', fillOpacity: 0.15, weight: 2, lineJoin: 'round'
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
            document.getElementById('detail-desc').innerText = data.description || 'Tidak ada keterangan tambahan.';

            const ownerInfo = document.getElementById('detail-owner-info');
            if(data.user && ownerInfo) {
                ownerInfo.classList.remove('hidden');
                document.getElementById('detail-owner-name').innerText = data.user.full_name;
                document.getElementById('detail-owner-email').innerText = data.user.email;
                document.getElementById('detail-owner-initials').innerText = data.user.full_name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
            }

            document.getElementById('detail-points-count').innerText = data.coordinates ? data.coordinates.length : 0;
            document.getElementById('detail-area').innerText = (data.properties && data.properties['Luas Lahan']) ? data.properties['Luas Lahan'] + ' Ha' : '-';

            const propsContainer = document.getElementById('detail-properties');
            propsContainer.innerHTML = '';
            if(data.properties) {
                Object.entries(data.properties).forEach(([k, v]) => {
                    if (k !== 'Luas Lahan') {
                        propsContainer.innerHTML += `
                            <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">${k}</span>
                                <span class="text-xs font-black text-gray-900">${v}</span>
                            </div>`;
                    }
                });
            }

            document.getElementById('btn-edit-detail').onclick = () => openForm('edit', id);
            document.getElementById('btn-delete-detail').onclick = () => confirmDeleteData(id, data.title);

            document.getElementById('detail-view').classList.remove('hidden');

            measurementLayer.clearLayers();
            savedLayer.eachLayer(l => {
                if(l.geoId == id) {
                    l.setStyle({ color: '#4f46e5', fillColor: '#4f46e5', fillOpacity: 0.3, weight: 4 });
                    map.fitBounds(l.getBounds(), { padding: [100, 100], animate: true });
                } else {
                    l.setStyle({ color: '#4f46e5', fillColor: '#4f46e5', fillOpacity: 0.15, weight: 2 });
                }
            });

            if(data.coordinates && data.coordinates.length > 0) {
                data.coordinates.forEach((p, i) => {
                    const marker = L.circleMarker(p, {
                        radius: 5, color: '#fff', weight: 3, fillColor: '#4f46e5', fillOpacity: 1
                    }).addTo(measurementLayer);
                    marker.bindTooltip(`<span class="font-black text-[9px] uppercase tracking-tighter">P${i+1}</span>`, {permanent: true, direction: 'top', className: 'bg-white border-none shadow-xl rounded-md px-1 py-0.5 text-primary-600'});
                });

                if (data.coordinates.length > 1) {
                    for (let i = 0; i < data.coordinates.length; i++) {
                        const p1 = data.coordinates[i];
                        const p2 = data.coordinates[(i + 1) % data.coordinates.length];
                        const from = turf.point([p1[1], p1[0]]);
                        const to = turf.point([p2[1], p2[0]]);
                        const distKm = turf.distance(from, to);
                        let distText = distKm < 1 ? (distKm * 1000).toFixed(0) + ' M' : distKm.toFixed(2) + ' KM';
                        const labelIcon = L.divIcon({
                            className: 'dist-label-container',
                            html: `<div class="dist-label">${distText}</div>`,
                            iconSize: [50, 20],
                            iconAnchor: [25, 10]
                        });
                        L.marker([(p1[0] + p2[0]) / 2, (p1[1] + p2[1]) / 2], {icon: labelIcon, interactive: false}).addTo(measurementLayer);
                    }
                }
            }
        };

        window.closeDetail = function() {
            document.getElementById('detail-view').classList.add('hidden');
            measurementLayer.clearLayers();
            savedLayer.eachLayer(l => l.setStyle({ color: '#4f46e5', fillColor: '#4f46e5', fillOpacity: 0.15, weight: 2 }));
        };

        // --- FORM & DATA DELETION ---

        // New function to handle Data Deletion via Modal
        window.confirmDeleteData = function(id, title) {
            document.getElementById('delete_item_title').innerText = title;
            document.getElementById('deleteDataForm').action = `/geoportal/${id}`;
            document.getElementById('deleteDataModal').classList.remove('hidden');
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
                addPropertyField('KOMODITAS', '');
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
                    if(points.length > 0) map.fitBounds(L.polygon(points).getBounds(), {padding: [100, 100]});
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

            if (type === 'lat') points[index][0] = val;
            else if (type === 'lng') points[index][1] = val;

            redrawDrawing(false); // redraw without refreshing inputs to keep focus
            calculateArea();
        }

        function confirmRemovePoint(index) {
            deletePointIndex = index;
            document.getElementById('confirmDeletePointModal').classList.remove('hidden');
        }

        function closeDeletePointModal() {
            document.getElementById('confirmDeletePointModal').classList.add('hidden');
            deletePointIndex = null;
        }

        function redrawDrawing(refreshInputs = true) {
            drawLayer.clearLayers();
            points.forEach((p, i) => {
                L.circleMarker(p, {color: '#ef4444', radius: 6, weight: 3, fillColor: '#fff', fillOpacity: 1}).addTo(drawLayer)
                 .bindTooltip(`<span class="font-black text-[9px] uppercase tracking-tighter">P${i+1}</span>`, {permanent: true, direction: 'right', className: 'bg-white border-none shadow-xl rounded-md px-1 py-0.5 text-red-600'});
            });
            if (points.length > 1) {
                L.polygon(points, {color: '#ef4444', dashArray: '8, 8', weight: 3, fillOpacity: 0.1, lineJoin: 'round'}).addTo(drawLayer);
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
                console.error("Area Calculation Error:", e);
            }
        }

        function renderInputs() {
            const container = document.getElementById('coords-container');
            container.innerHTML = '';
            points.forEach((p, i) => {
                container.innerHTML += `
                    <div class="flex gap-2 items-center bg-white p-3 rounded-2xl border border-gray-100 shadow-sm mb-2 group">
                        <div class="w-8 h-8 rounded-xl bg-gray-50 flex items-center justify-center font-black text-gray-400 text-[10px] flex-shrink-0">P${i+1}</div>
                        <div class="flex-1 grid grid-cols-2 gap-2">
                             <input type="number" step="any" value="${p[0]}" oninput="updateCoordinate(${i}, 'lat', this.value)" class="w-full text-[10px] font-black tracking-widest bg-gray-50 border-none focus:ring-2 focus:ring-primary-500 rounded-lg p-2" placeholder="LAT">
                             <input type="number" step="any" value="${p[1]}" oninput="updateCoordinate(${i}, 'lng', this.value)" class="w-full text-[10px] font-black tracking-widest bg-gray-50 border-none focus:ring-2 focus:ring-primary-500 rounded-lg p-2" placeholder="LNG">
                        </div>
                        <button type="button" onclick="confirmRemovePoint(${i})" class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
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
            div.className = 'flex gap-2 items-center';
            div.innerHTML = `
                <input type="text" name="keys[]" value="${key}" class="w-1/3 text-[10px] font-black uppercase tracking-widest bg-gray-50 border-none focus:ring-2 focus:ring-primary-500 rounded-xl p-3" placeholder="LABEL">
                <input type="text" name="values[]" value="${value}" class="flex-1 text-xs font-bold bg-gray-50 border-none focus:ring-2 focus:ring-primary-500 rounded-xl p-3" placeholder="VALUE">
                <button type="button" onclick="this.parentElement.remove()" class="p-3 text-red-500 hover:bg-red-50 rounded-xl"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
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
                openIcon.classList.add('hidden'); closedIcon.classList.remove('hidden'); btn.closest('.geo-item').classList.add('opacity-40');
            } else if(window.hiddenCache && window.hiddenCache[id]) {
                savedLayer.addLayer(window.hiddenCache[id]);
                delete window.hiddenCache[id];
                openIcon.classList.remove('hidden'); closedIcon.classList.add('hidden'); btn.closest('.geo-item').classList.remove('opacity-40');
            }
        };
    </script>
</x-app-layout>
