<x-app-layout>
    <div class="max-w-5xl mx-auto py-6">

        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Pengaturan Profil</h2>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Kelola informasi pribadi, jabatan, dan keamanan akun Anda.</p>
        </div>

        <!-- Form Container -->
        <form action="{{ route('user.profile.update') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            @method('PUT')

            <!-- KOLOM KIRI: Data Diri & Keamanan -->
            <div class="lg:col-span-1 space-y-6">

                <!-- Card Foto & Info Dasar -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6 text-center">
                    <div class="w-24 h-24 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-4">
                        {{ substr($user->full_name, 0, 1) }}
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->username }}</h3>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    <div class="mt-4">
                        <label class="block text-left mb-1 text-xs font-medium text-gray-500 uppercase">Nama Lengkap</label>
                        <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-semibold text-center" required>
                    </div>
                </div>

                <!-- Card Kontak (HP) -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        Kontak WhatsApp
                    </h4>
                    <div class="relative">
                        <input type="text" id="current_phone" value="{{ $user->phone_number }}" class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400 cursor-not-allowed" readonly>
                        <button type="button" data-modal-target="changePhoneModal" data-modal-toggle="changePhoneModal" class="absolute end-2 top-1.5 text-xs bg-primary-600 text-white px-3 py-1 rounded hover:bg-primary-700 transition">
                            Ubah
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Nomor harus aktif untuk menerima OTP.</p>
                </div>

                <!-- Tombol Ganti Password (Trigger Modal) -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Keamanan
                    </h4>
                    <button type="button" data-modal-target="changePasswordModal" data-modal-toggle="changePasswordModal" class="w-full text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 flex items-center justify-center gap-2">
                        Ganti Password
                    </button>
                </div>
            </div>

            <!-- KOLOM KANAN: Detail Pekerjaan & Alamat -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-8">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-4 border-gray-200 dark:border-gray-700">
                        Detail Pekerjaan & Lokasi
                    </h3>

                    <!-- Grid Jabatan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="job_title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jabatan <span class="text-red-500">*</span></label>
                            <select id="job_title" name="job_title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach(['Staff', 'Analis', 'Supervisor', 'Manajer', 'Kepala Divisi', 'Direktur', 'CEO', 'CTO', 'CIO', 'COO'] as $j)
                                    <option value="{{ $j }}" {{ old('job_title', $profile->job_title) == $j ? 'selected' : '' }}>{{ $j }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="job_sector" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bidang Pekerjaan <span class="text-red-500">*</span></label>
                            <select id="job_sector" name="job_sector" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                                <option value="">Pilih Sektor</option>
                                @foreach(['Teknologi', 'Keuangan', 'Manufaktur', 'Perdagangan', 'Pendidikan', 'Kesehatan', 'Logistik', 'Pemerintahan', 'Energi', 'Kreatif'] as $s)
                                    <option value="{{ $s }}" {{ old('job_sector', $profile->job_sector) == $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="nik" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NIK (Opsional)</label>
                            <input type="text" id="nik" name="nik" value="{{ old('nik', $profile->additional_primary_fields['nik'] ?? '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Nomor Induk Kependudukan">
                        </div>
                    </div>

                    <!-- Section Alamat -->
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4 mt-8 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Alamat Kantor
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <!-- Provinsi -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Provinsi <span class="text-red-500">*</span></label>
                            <select id="province_select" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                <option value="">Memuat Provinsi...</option>
                            </select>
                            <input type="hidden" name="province_id" id="province_id" value="{{ old('province_id', $profile->province_id) }}">
                            <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name', $profile->province_name) }}">
                        </div>

                        <!-- Kota/Kabupaten -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kota/Kabupaten <span class="text-red-500">*</span></label>
                            <select id="city_select" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" disabled required>
                                <option value="">Pilih Provinsi Dulu</option>
                            </select>
                            <input type="hidden" name="city_id" id="city_id" value="{{ old('city_id', $profile->city_id) }}">
                            <input type="hidden" name="city_name" id="city_name" value="{{ old('city_name', $profile->city_name) }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                        <!-- Kode Pos -->
                        <div class="md:col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Pos <span class="text-red-500">*</span></label>
                            <input type="text" name="postal_code" value="{{ old('postal_code', $profile->postal_code) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                        </div>
                        <!-- Detail Jalan -->
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Jalan / Gedung <span class="text-red-500">*</span></label>
                            <input type="text" name="company_address" value="{{ old('company_address', $profile->company_address) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Jl. Jenderal Sudirman No. 1..." required>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                        <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-base px-8 py-3 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 shadow-lg transform transition hover:-translate-y-0.5">
                            Simpan Perubahan Profil
                        </button>
                    </div>
                </div>
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
