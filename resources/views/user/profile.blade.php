<x-app-layout>
    <div class="max-w-5xl mx-auto py-6">

    <x-page-header 
        title="Pengaturan Profil" 
        subtitle="Kelola informasi pribadi, identitas, dan keamanan akun Anda." 
    />

    <form action="{{ route('user.profile.update') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        @method('PUT')

        <!-- SIDEBAR INFO -->
        <div class="space-y-6">
            <x-content-card class="text-center">
                <div class="w-20 h-20 bg-primary-100 text-primary-600 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-sm border border-primary-200">
                    {{ substr($user->full_name, 0, 1) }}
                </div>
                <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ $user->full_name }}</h3>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ '@' . $user->username }}</p>
                <div class="mt-6 pt-6 border-t border-gray-50 flex flex-col gap-3">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-gray-400 font-medium">Email</span>
                        <span class="text-gray-900 font-bold">{{ $user->email }}</span>
                    </div>
                </div>
            </x-content-card>

            <x-content-card>
                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Kontak & Keamanan</h4>
                <div class="space-y-4">
                    <div>
                        <label class="block mb-1.5 text-[10px] font-bold text-gray-400 uppercase">Nomor WhatsApp</label>
                        <div class="relative">
                            <input type="text" value="{{ $user->phone_number }}" class="bg-gray-50 border-gray-100 text-gray-600 text-xs rounded-xl block w-full p-3 font-bold cursor-not-allowed" readonly>
                            <button type="button" data-modal-target="changePhoneModal" data-modal-toggle="changePhoneModal" class="absolute end-2 top-2 text-[10px] font-bold bg-primary-600 text-white px-3 py-1.5 rounded-lg hover:bg-primary-700 transition shadow-sm uppercase">Ubah</button>
                        </div>
                    </div>
                    <button type="button" data-modal-target="changePasswordModal" data-modal-toggle="changePasswordModal" class="w-full py-3 border border-gray-100 text-gray-900 font-bold rounded-xl text-xs hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Ganti Password
                    </button>
                </div>
            </x-content-card>
        </div>

        <!-- MAIN FORM -->
        <div class="lg:col-span-2 space-y-6">
            <x-content-card>
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-8 border-b border-gray-50 pb-4">Personal & Professional Info</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block mb-2 text-xs font-bold text-gray-400 uppercase">Nama Lengkap</label>
                        <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 font-medium" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-xs font-bold text-gray-400 uppercase">NIK (KTP)</label>
                        <input type="text" name="nik" value="{{ old('nik', $profile->additional_primary_fields['nik'] ?? '') }}" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 font-medium" placeholder="16 digit NIK">
                    </div>
                    <div>
                        <label class="block mb-2 text-xs font-bold text-gray-400 uppercase">Jabatan</label>
                        <select name="job_title" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 font-medium" required>
                            <option value="">Pilih Jabatan</option>
                            @foreach(['Staff', 'Analis', 'Supervisor', 'Manajer', 'Kepala Divisi', 'Direktur', 'CEO', 'CTO', 'CIO', 'COO'] as $j)
                                <option value="{{ $j }}" {{ old('job_title', $profile->job_title) == $j ? 'selected' : '' }}>{{ $j }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-xs font-bold text-gray-400 uppercase">Bidang Pekerjaan</label>
                        <select name="job_sector" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 font-medium" required>
                            <option value="">Pilih Sektor</option>
                            @foreach(['Teknologi', 'Keuangan', 'Manufaktur', 'Perdagangan', 'Pendidikan', 'Kesehatan', 'Logistik', 'Pemerintahan', 'Energi', 'Kreatif'] as $s)
                                <option value="{{ $s }}" {{ old('job_sector', $profile->job_sector) == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-8 border-b border-gray-50 pb-4 mt-12">Lokasi Kantor</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Provinsi</label>
                        <select id="province_select" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 font-medium" required>
                            <option value="">Memuat Provinsi...</option>
                        </select>
                        <input type="hidden" name="province_id" id="province_id" value="{{ old('province_id', $profile->province_id) }}">
                        <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name', $profile->province_name) }}">
                    </div>
                    <div>
                        <label class="block mb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Kota/Kabupaten</label>
                        <select id="city_select" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 font-medium" disabled required>
                            <option value="">Pilih Provinsi Dulu</option>
                        </select>
                        <input type="hidden" name="city_id" id="city_id" value="{{ old('city_id', $profile->city_id) }}">
                        <input type="hidden" name="city_name" id="city_name" value="{{ old('city_name', $profile->city_name) }}">
                    </div>
                    <div>
                        <label class="block mb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Kode Pos</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $profile->postal_code) }}" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 font-bold" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Alamat Lengkap</label>
                        <input type="text" name="company_address" value="{{ old('company_address', $profile->company_address) }}" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 font-medium" placeholder="Gedung, Lantai, No. Kantor..." required>
                    </div>
                </div>

                <div class="pt-8 border-t border-gray-50 flex justify-end">
                    <button type="submit" class="text-white bg-primary-600 hover:bg-primary-700 font-bold rounded-xl text-sm px-10 py-4 transition-all shadow-sm active:scale-95 uppercase tracking-wider">
                        Simpan Profil
                    </button>
                </div>
            </x-content-card>
        </div>
    </form>
    </div>

    <!-- MODAL 1: GANTI HP (OTP) -->
    <div id="changePhoneModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ganti Nomor WhatsApp</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="changePhoneModal">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <div class="p-4 md:p-5">
                    <!-- Step 1 -->
                    <div id="phone-step-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor Baru</label>
                        <input type="text" id="input_new_phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 mb-4" placeholder="0812...">
                        <button type="button" id="btn-request-otp" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Kirim OTP</button>
                    </div>
                    <!-- Step 2 -->
                    <div id="phone-step-2" class="hidden">
                        <p class="text-sm text-gray-500 mb-4 text-center">Masukkan kode OTP.</p>
                        <input type="text" id="input_otp" class="bg-gray-50 border border-gray-300 text-gray-900 text-2xl font-bold text-center rounded-lg block w-full p-2.5 mb-4" placeholder="000000">
                        <button type="button" id="btn-verify-otp" class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Verifikasi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 2: GANTI PASSWORD (NEW) -->
    <div id="changePasswordModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ganti Password</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="changePasswordModal">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <form action="{{ route('user.profile.password') }}" method="POST" class="p-4 md:p-5">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password Lama</label>
                        <input type="password" name="current_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password Baru</label>
                        <input type="password" name="new_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                    </div>
                    <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan Password</button>
                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPT FIXED (ASYNC REGION) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const provinceSelect = document.getElementById('province_select');
            const citySelect = document.getElementById('city_select');
            const hiddenProvId = document.getElementById('province_id');
            const hiddenProvName = document.getElementById('province_name');
            const hiddenCityId = document.getElementById('city_id');
            const hiddenCityName = document.getElementById('city_name');

            // Saved Values (from DB)
            const savedProvId = "{{ old('province_id', $profile->province_id) }}";
            const savedCityId = "{{ old('city_id', $profile->city_id) }}";

            // 1. Load Provinsi (Async)
            async function loadProvinces() {
                try {
                    const response = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json`);
                    const provinces = await response.json();

                    provinceSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                    provinces.forEach(prov => {
                        const selected = prov.id == savedProvId ? 'selected' : '';
                        provinceSelect.innerHTML += `<option value="${prov.id}" data-name="${prov.name}" ${selected}>${prov.name}</option>`;
                    });

                    // Jika ada savedProvId, load kota juga
                    if(savedProvId) {
                        await loadCities(savedProvId, savedCityId);
                    }
                } catch (error) {
                    console.error('Gagal memuat provinsi:', error);
                }
            }

            // 2. Load Kota (Async)
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

            // Init Load
            loadProvinces();

            // Event Listeners
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

            // OTP Logic (Dengan Toast)
            const btnRequestOtp = document.getElementById('btn-request-otp');
            const btnVerifyOtp = document.getElementById('btn-verify-otp');
            const step1 = document.getElementById('phone-step-1');
            const step2 = document.getElementById('phone-step-2');

            btnRequestOtp.addEventListener('click', function() {
                const newPhone = document.getElementById('input_new_phone').value;
                if(newPhone.length < 10) { showToast('Nomor HP tidak valid', 'error'); return; }

                this.disabled = true; this.innerHTML = 'Mengirim...';

                fetch('{{ route("user.profile.phone.otp") }}', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: JSON.stringify({ new_phone: newPhone })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        step1.classList.add('hidden'); step2.classList.remove('hidden');
                        showToast('OTP terkirim ke WhatsApp', 'success');
                    } else {
                        showToast(data.message, 'error');
                        btnRequestOtp.disabled = false; btnRequestOtp.innerHTML = 'Kirim OTP';
                    }
                });
            });

            btnVerifyOtp.addEventListener('click', function() {
                const otp = document.getElementById('input_otp').value;
                this.disabled = true; this.innerHTML = 'Verifikasi...';

                fetch('{{ route("user.profile.phone.verify") }}', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: JSON.stringify({ otp: otp })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        showToast('Nomor HP berhasil diubah!', 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast(data.message, 'error');
                        btnVerifyOtp.disabled = false; btnVerifyOtp.innerHTML = 'Verifikasi';
                    }
                });
            });
        });
    </script>
</x-app-layout>
