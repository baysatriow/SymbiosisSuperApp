<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            Verifikasi WhatsApp
        </h1>
        <p class="text-sm text-gray-500 mt-2">
            Masukkan kode OTP 6 digit yang dikirim ke WhatsApp Anda.
        </p>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-400" role="alert">
            {{ session('warning') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <form class="space-y-4 md:space-y-6" action="{{ route('otp.verify') }}" method="POST">
        @csrf
        <div>
            <label for="otp_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-center">Kode OTP</label>
            <input type="text" name="otp_code" id="otp_code" maxlength="6" class="text-center tracking-[1em] text-2xl font-bold bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="000000" required autofocus autocomplete="off">
        </div>

        <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Verifikasi & Masuk</button>
    </form>

    <!-- Bagian Resend OTP dengan Timer -->
    <div class="mt-4 text-center">
        <form action="{{ route('otp.resend') }}" method="POST" id="resendForm">
            @csrf
            <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                Tidak menerima kode?
                <br>
                <button type="submit" id="resendBtn" class="font-medium text-gray-400 cursor-not-allowed mt-2" disabled>
                    Kirim Ulang (<span id="timer">{{ $waitTime ?? 60 }}</span>s)
                </button>
            </p>
        </form>
    </div>

    <div class="text-center mt-4 border-t pt-4">
         <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-primary-600">Kembali ke Login</a>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const WAIT_TIME = {{ $waitTime ?? 60 }};
        const timerElement = document.getElementById('timer');
        const resendBtn = document.getElementById('resendBtn');

        const STORAGE_KEY = "otp_login_expire_at";

        // Ambil expireAt dari localStorage
        let expireAt = localStorage.getItem(STORAGE_KEY);

        // Jika belum ada → buat baru
        if (!expireAt) {
            expireAt = Date.now() + WAIT_TIME * 1000;
            localStorage.setItem(STORAGE_KEY, expireAt);
        } else {
            expireAt = parseInt(expireAt);
        }

        function updateTimer() {
            const now = Date.now();
            let timeLeft = Math.floor((expireAt - now) / 1000);

            if (timeLeft > 0) {

                timerElement.textContent = String(timeLeft).padStart(2, '0');

                resendBtn.disabled = true;
                resendBtn.classList.add('text-gray-400', 'cursor-not-allowed');
                resendBtn.classList.remove('text-primary-600', 'hover:underline');

                setTimeout(updateTimer, 1000);
            } else {
                // Timer habis
                timerElement.textContent = "00";

                resendBtn.innerHTML = "Kirim Ulang Sekarang";
                resendBtn.disabled = false;
                resendBtn.classList.remove('text-gray-400', 'cursor-not-allowed');
                resendBtn.classList.add('text-primary-600', 'hover:underline');

                // Hapus expireAt supaya timer reset saat user klik resend
                localStorage.removeItem(STORAGE_KEY);
            }
        }

        updateTimer();

        // Bersihkan timer ketika user klik resend (langsung reset dari server)
        document.getElementById('resendForm')?.addEventListener('submit', () => {
            localStorage.removeItem(STORAGE_KEY);
        });

    });
    </script>

</x-guest-layout>
