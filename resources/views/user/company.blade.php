<x-app-layout>
    <div class="max-w-6xl mx-auto py-6">

    <x-page-header 
        title="Profil Perusahaan" 
        subtitle="Lengkapi data legalitas dan operasional perusahaan untuk verifikasi dokumen." 
    />

    @if(session('success'))
        <x-content-card class="bg-emerald-50 border-emerald-100 flex items-center gap-4 mb-8 !py-4">
            <div class="bg-emerald-100 p-2 rounded-lg text-emerald-600">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
            </div>
            <p class="text-xs text-emerald-800 font-bold uppercase tracking-wider">Berhasil Disimpan!</p>
        </x-content-card>
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
                <!-- SIDEBAR LEGAL -->
                <div class="space-y-6">
                    <x-content-card>
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Identitas Legal</h4>
                        <div class="space-y-5">
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase">Badan Usaha</label>
                                <select name="legal_entity_type" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 font-bold" required>
                                    <option value="">Pilih...</option>
                                    @foreach(['PT', 'CV', 'Fund', 'Org', 'UMKM', 'Koperasi', 'Yayasan'] as $type)
                                        <option value="{{ $type }}" {{ old('legal_entity_type', $company->legal_entity_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase">Nama Entitas</label>
                                <input type="text" name="company_name" value="{{ old('company_name', $company->company_name) }}" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 font-bold" required>
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase">NPWP</label>
                                <input type="text" name="tax_id_npwp" value="{{ old('tax_id_npwp', $company->tax_id_npwp) }}" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 font-medium" placeholder="00.000.000.0-000.000">
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase">No. NIB</label>
                                <input type="text" name="nib_number" value="{{ old('nib_number', $company->nib_number) }}" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 font-medium">
                            </div>
                        </div>
                    </x-content-card>

                    <x-content-card>
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Operasional</h4>
                         <div class="space-y-5">
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase">Sektor Industri</label>
                                <select name="sector" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 font-medium" required>
                                    <option value="">Pilih Sektor</option>
                                    @foreach(['Teknologi', 'Keuangan', 'Manufaktur', 'Perdagangan', 'Pendidikan', 'Kesehatan', 'Logistik', 'Pemerintahan', 'Energi', 'Kreatif', 'Pertanian', 'Konstruksi'] as $s)
                                        <option value="{{ $s }}" {{ old('sector', $company->sector) == $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-2 text-xs font-bold text-gray-400 uppercase">Tahun</label>
                                    <input type="number" name="year_founded" value="{{ old('year_founded', $company->year_founded) }}" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl block w-full p-3 font-medium" placeholder="YYYY">
                                </div>
                                <div>
                                    <label class="block mb-2 text-xs font-bold text-gray-400 uppercase">Karyawan</label>
                                    <input type="number" name="size_employees" value="{{ old('size_employees', $company->size_employees) }}" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl block w-full p-3 font-medium">
                                </div>
                            </div>
                         </div>
                    </x-content-card>
                </div>

                <!-- MAIN INFO -->
                <div class="lg:col-span-2 space-y-6">
                    <x-content-card>
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-8 border-b border-gray-50 pb-4">Kontak & Lokasi</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase">Email Kantor</label>
                                <input type="email" name="contact_email" value="{{ old('contact_email', $company->contact_email) }}" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 font-medium" required>
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase">Telepon Kantor</label>
                                <input type="text" name="contact_phone" value="{{ old('contact_phone', $company->contact_phone) }}" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 font-medium" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase">Website Resmi</label>
                                <input type="url" name="website" value="{{ old('website', $company->website) }}" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 font-medium" placeholder="https://...">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 mt-12">
                            <div class="md:col-span-2">
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Alamat Operasional</h4>
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Provinsi</label>
                                <select id="province_select" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl block w-full p-3 font-medium" required>
                                    <option value="">Memuat Data...</option>
                                </select>
                                <input type="hidden" name="province_id" id="province_id" value="{{ old('province_id', $company->province_id) }}">
                                <input type="hidden" name="province_name" id="province_name" value="{{ old('province', $company->province) }}">
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Kota/Kabupaten</label>
                                <select id="city_select" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl block w-full p-3 font-medium" disabled required>
                                    <option value="">Pilih Provinsi Dulu</option>
                                </select>
                                <input type="hidden" name="city_id" id="city_id" value="{{ old('city_id', $company->city_id) }}">
                                <input type="hidden" name="city_name" id="city_name" value="{{ old('city', $company->city) }}">
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Kode Pos</label>
                                <input type="text" name="postal_code" value="{{ old('postal_code', $company->postal_code) }}" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl block w-full p-3 font-bold" required>
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Detail Jalan</label>
                                <input type="text" name="address" value="{{ old('address', $company->address) }}" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl block w-full p-3 font-medium" required>
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block mb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Deskripsi Perusahaan</label>
                            <textarea name="description" rows="4" class="block w-full text-sm text-gray-900 bg-gray-50 rounded-xl border-gray-100 focus:ring-primary-500 focus:border-primary-500 p-4 font-medium" placeholder="Visi misi atau kegiatan utama bisnis...">{{ old('description', $company->description) }}</textarea>
                        </div>

                        <div class="pt-8 border-t border-gray-50 flex justify-end">
                            <button type="submit" class="text-white bg-primary-600 hover:bg-primary-700 font-bold rounded-xl text-sm px-10 py-4 transition-all shadow-sm active:scale-95 uppercase tracking-wider">
                                Simpan Profil Perusahaan
                            </button>
                        </div>
                    </x-content-card>
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
