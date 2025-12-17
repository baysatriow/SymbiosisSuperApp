<x-app-layout>
    <div class="max-w-6xl mx-auto py-6">

        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Profil Perusahaan</h2>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Lengkapi data legalitas dan operasional perusahaan untuk verifikasi dokumen.</p>
        </div>

        <!-- Alert Success/Error -->
        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 text-center shadow-sm">
                <span class="font-bold">Sukses!</span> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 shadow-sm">
                <ul class="list-disc list-inside mx-auto max-w-lg">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.company.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- KOLOM KIRI: IDENTITAS UTAMA -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Identitas Legal
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block mb-2 text-xs font-medium text-gray-500 uppercase">Bentuk Badan Usaha <span class="text-red-500">*</span></label>
                                <select name="legal_entity_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    <option value="">Pilih...</option>
                                    @foreach(['PT', 'CV', 'Fund', 'Org', 'UMKM', 'Koperasi', 'Yayasan'] as $type)
                                        <option value="{{ $type }}" {{ old('legal_entity_type', $company->legal_entity_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block mb-2 text-xs font-medium text-gray-500 uppercase">Nama Perusahaan <span class="text-red-500">*</span></label>
                                <input type="text" name="company_name" value="{{ old('company_name', $company->company_name) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-semibold" placeholder="Contoh: Maju Mundur" required>
                            </div>

                            <div>
                                <label class="block mb-2 text-xs font-medium text-gray-500 uppercase">NPWP Perusahaan</label>
                                <input type="text" name="tax_id_npwp" value="{{ old('tax_id_npwp', $company->tax_id_npwp) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="00.000.000.0-000.000">
                            </div>

                            <div>
                                <label class="block mb-2 text-xs font-medium text-gray-500 uppercase">NIB (Nomor Induk Berusaha)</label>
                                <input type="text" name="nib_number" value="{{ old('nib_number', $company->nib_number) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Nomor NIB...">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Detail Operasional
                        </h3>
                         <div class="space-y-4">
                            <div>
                                <label class="block mb-2 text-xs font-medium text-gray-500 uppercase">Sektor Industri <span class="text-red-500">*</span></label>
                                <select name="sector" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    <option value="">Pilih Sektor</option>
                                    @foreach(['Teknologi', 'Keuangan', 'Manufaktur', 'Perdagangan', 'Pendidikan', 'Kesehatan', 'Logistik', 'Pemerintahan', 'Energi', 'Kreatif', 'Pertanian', 'Konstruksi'] as $s)
                                        <option value="{{ $s }}" {{ old('sector', $company->sector) == $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-2 text-xs font-medium text-gray-500 uppercase">Tahun Berdiri</label>
                                    <input type="number" name="year_founded" value="{{ old('year_founded', $company->year_founded) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="YYYY">
                                </div>
                                <div>
                                    <label class="block mb-2 text-xs font-medium text-gray-500 uppercase">Jml Karyawan</label>
                                    <input type="number" name="size_employees" value="{{ old('size_employees', $company->size_employees) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="ex: 50">
                                </div>
                            </div>
                         </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: ALAMAT & KONTAK -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-8">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-4 border-gray-200 dark:border-gray-700 flex items-center gap-2">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Lokasi & Kontak Perusahaan
                        </h3>

                        <!-- Form Alamat (API Integration) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Provinsi -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Provinsi <span class="text-red-500">*</span></label>
                                <select id="province_select" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    <option value="">Memuat Data...</option>
                                </select>
                                <input type="hidden" name="province_id" id="province_id" value="{{ old('province_id', $company->province_id) }}">
                                <input type="hidden" name="province_name" id="province_name" value="{{ old('province', $company->province) }}">
                            </div>

                            <!-- Kota/Kabupaten -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kota/Kabupaten <span class="text-red-500">*</span></label>
                                <select id="city_select" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" disabled required>
                                    <option value="">Pilih Provinsi Dulu</option>
                                </select>
                                <input type="hidden" name="city_id" id="city_id" value="{{ old('city_id', $company->city_id) }}">
                                <input type="hidden" name="city_name" id="city_name" value="{{ old('city', $company->city) }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <!-- Kode Pos -->
                            <div class="md:col-span-1">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Pos <span class="text-red-500">*</span></label>
                                <input type="text" name="postal_code" value="{{ old('postal_code', $company->postal_code) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="12345" maxlength="5" required>
                            </div>
                            <!-- Detail Jalan -->
                            <div class="md:col-span-2">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat Lengkap (Jalan/Gedung) <span class="text-red-500">*</span></label>
                                <input type="text" name="address" value="{{ old('address', $company->address) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Jl. Sudirman Kav. 5..." required>
                            </div>
                        </div>

                        <hr class="my-6 border-gray-200 dark:border-gray-700">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email Perusahaan <span class="text-red-500">*</span></label>
                                <input type="email" name="contact_email" value="{{ old('contact_email', $company->contact_email) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="info@perusahaan.com" required>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No. Telepon Kantor <span class="text-red-500">*</span></label>
                                <input type="text" name="contact_phone" value="{{ old('contact_phone', $company->contact_phone) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="021-5555xxx" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Website</label>
                                <input type="url" name="website" value="{{ old('website', $company->website) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="https://www.perusahaan.com">
                            </div>
                        </div>

                        <div class="mb-6">
                             <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi Singkat</label>
                            <textarea name="description" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Jelaskan secara singkat tentang perusahaan Anda...">{{ old('description', $company->description) }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-base px-8 py-3 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 shadow-lg transform transition hover:-translate-y-0.5">
                                Simpan Profil Perusahaan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- SCRIPT API WILAYAH (ASYNC) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province_select');
            const citySelect = document.getElementById('city_select');
            const hiddenProvId = document.getElementById('province_id');
            const hiddenProvName = document.getElementById('province_name');
            const hiddenCityId = document.getElementById('city_id');
            const hiddenCityName = document.getElementById('city_name');

            // Ambil data tersimpan (jika ada)
            const savedProvId = "{{ old('province_id', $company->province_id) }}";
            const savedCityId = "{{ old('city_id', $company->city_id) }}";

            // 1. Load Provinsi
            async function loadProvinces() {
                try {
                    const response = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json`);
                    const provinces = await response.json();

                    provinceSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                    provinces.forEach(prov => {
                        const selected = prov.id == savedProvId ? 'selected' : '';
                        provinceSelect.innerHTML += `<option value="${prov.id}" data-name="${prov.name}" ${selected}>${prov.name}</option>`;
                    });

                    if(savedProvId) {
                        await loadCities(savedProvId, savedCityId);
                    }
                } catch (error) {
                    console.error('Gagal memuat provinsi:', error);
                }
            }

            // 2. Load Kota
            async function loadCities(provId, preSelectedId = null) {
                try {
                    citySelect.disabled = true;
                    citySelect.innerHTML = '<option value="">Memuat...</option>';

                    const response = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`);
                    const cities = await response.json();

                    citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                    citySelect.disabled = false;

                    cities.forEach(city => {
                        const selected = city.id == preSelectedId ? 'selected' : '';
                        citySelect.innerHTML += `<option value="${city.id}" data-name="${city.name}" ${selected}>${city.name}</option>`;
                    });
                } catch (error) {
                    console.error('Gagal memuat kota:', error);
                    citySelect.innerHTML = '<option value="">Gagal memuat data</option>';
                }
            }

            loadProvinces();

            provinceSelect.addEventListener('change', function() {
                const provId = this.value;
                const provName = this.options[this.selectedIndex].getAttribute('data-name');
                hiddenProvId.value = provId;
                hiddenProvName.value = provName;
                if(provId) loadCities(provId);
            });

            citySelect.addEventListener('change', function() {
                hiddenCityId.value = this.value;
                hiddenCityName.value = this.options[this.selectedIndex].getAttribute('data-name');
            });
        });
    </script>
</x-app-layout>
