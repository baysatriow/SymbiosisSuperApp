<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            Verifikasi Reset
        </h1>
        <p class="text-sm text-gray-500 mt-2">
            Masukkan kode OTP 6 digit yang dikirim ke WhatsApp Anda untuk melanjutkan reset password.
        </p>
    </div>

    <form class="space-y-4 md:space-y-6" action="{{ route('password.otp.verify') }}" method="POST">
        @csrf
        <div>
            <label for="otp" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-center">Kode OTP (6 Digit)</label>
            <input type="text" name="otp" id="otp" maxlength="6" class="text-center tracking-[1em] text-2xl font-bold bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="000000" required autofocus autocomplete="off">
        </div>

        <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
            Verifikasi & Lanjut
        </button>
    </form>

    <!-- Bagian Resend OTP dengan Timer -->
    <div class="mt-4 text-center">
        <form action="{{ route('password.otp.resend') }}" method="POST" id="resendForm">
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

    <div class="text-center mt-4 border-t pt-4 dark:border-gray-700">
         <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-primary-600 dark:text-gray-400">Batal & Kembali ke Login</a>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const WAIT_TIME = {{ $waitTime ?? 60 }};
        const timerElement = document.getElementById('timer');
        const resendBtn = document.getElementById('resendBtn');

        // Key untuk localStorage
        const STORAGE_KEY = "reset_otp_expire_at";

        // Ambil expire time dari localStorage
        let expireAt = localStorage.getItem(STORAGE_KEY);

        // Kalau belum ada, buat expireAt baru
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

                // Hapus expireAt agar jika klik tombol → timer reset dari server
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
