<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            Lupa Password?
        </h1>
        <p class="text-sm text-gray-500 mt-2">
            Masukkan email atau username Anda. Kami akan mengirimkan kode verifikasi (OTP) ke WhatsApp terdaftar Anda.
        </p>
    </div>

    <form class="space-y-4 md:space-y-6" action="{{ route('password.email') }}" method="POST">
        @csrf
        <div>
            <label for="login" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email atau Username</label>
            <input type="text" name="login" id="login" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="name@company.com / username" required>
        </div>

        <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            Kirim Kode OTP
        </button>

        <div class="text-center mt-4">
             <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-primary-600 dark:text-gray-400">Kembali ke Login</a>
        </div>
    </form>
</x-guest-layout>
