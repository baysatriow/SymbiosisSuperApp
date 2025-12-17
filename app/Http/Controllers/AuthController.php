<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OtpCode;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthController extends Controller
{
    protected $fonnteService;

    public function __construct(FonnteService $fonnteService)
    {
        $this->fonnteService = $fonnteService;
    }

    /* ============================================================
     * OTP HELPER
     * ============================================================ */
    private function generateAndSendOtp($user, $purpose)
    {
        // Menandai OTP lama sebagai consumed
        OtpCode::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->update(['consumed_at' => Carbon::now()]);

        $code = rand(100000, 999999);

        OtpCode::create([
            'user_id'    => $user->id,
            'code'       => $code,
            'purpose'    => $purpose,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        // Mapping tujuan OTP
        $actionMap = [
            'register'        => 'Pendaftaran',
            'login'           => 'Masuk',
            'password_reset'  => 'Reset Password'
        ];

        $actionName = $actionMap[$purpose] ?? 'Verifikasi';

        $message = "Kode OTP Symbiosis ($actionName): *$code*\n".
                   "Jangan berikan kepada siapapun. Berlaku 5 menit.";

        $this->fonnteService->sendWhatsApp($user->phone_number, $message);
    }

    /* ============================================================
     * FORGOT PASSWORD – STEP 1: INPUT USERNAME/EMAIL
     * ============================================================ */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate(['login' => 'required|string']);

        $user = User::where('email', $request->login)
            ->orWhere('username', $request->login)
            ->first();

        if (!$user) {
            return back()->with('error', 'Akun tidak ditemukan.');
        }

        $this->generateAndSendOtp($user, 'password_reset');

        session(['reset_user_id' => $user->id]);

        return redirect()
            ->route('password.otp.form')
            ->with('success', 'OTP telah dikirim ke WhatsApp Anda.');
    }

    /* ============================================================
     * FORGOT PASSWORD – STEP 2: OTP VERIFICATION
     * ============================================================ */
    public function showResetOtpForm()
    {
        if (!session('reset_user_id')) {
            return redirect()->route('password.request');
        }

        // Hitung sisa waktu untuk tombol resend
        $userId   = session('reset_user_id');
        $lastOtp  = OtpCode::where('user_id', $userId)
                    ->where('purpose', 'password_reset')
                    ->latest()
                    ->first();

        $secondsSince = $lastOtp
            ? Carbon::now()->diffInSeconds($lastOtp->created_at)
            : 60;

        $waitTime = max(0, 60 - $secondsSince);

        return view('auth.forgot-password-otp', compact('waitTime'));
    }

    public function resendResetOtp()
    {
        $userId = session('reset_user_id');
        if (!$userId) return redirect()->route('password.request');

        $user = User::find($userId);

        // Batasi 1 OTP / 60 detik
        $lastOtp = OtpCode::where('user_id', $userId)
            ->where('purpose', 'password_reset')
            ->latest()
            ->first();

        if ($lastOtp && Carbon::now()->diffInSeconds($lastOtp->created_at) < 60) {
            return back()->with('error', 'Harap tunggu 1 menit sebelum meminta kode baru.');
        }

        $this->generateAndSendOtp($user, 'password_reset');

        return back()->with('success', 'Kode OTP baru telah dikirim.');
    }

    public function verifyResetOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        $userId = session('reset_user_id');
        if (!$userId) return redirect()->route('password.request');

        $otpRecord = OtpCode::where('user_id', $userId)
            ->where('code', $request->otp)
            ->where('purpose', 'password_reset')
            ->whereNull('consumed_at')
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return back()->with('error', 'Kode OTP salah atau kadaluarsa.');
        }

        $otpRecord->update(['consumed_at' => Carbon::now()]);
        session(['reset_verified' => true]);

        return redirect()
            ->route('password.reset.form')
            ->with('success', 'Verifikasi berhasil. Silakan buat password baru.');
    }

    /* ============================================================
     * FORGOT PASSWORD – STEP 3: RESET PASSWORD
     * ============================================================ */
    public function showResetPasswordForm()
    {
        if (!session('reset_verified') || !session('reset_user_id')) {
            return redirect()->route('password.request')
                ->with('error', 'Sesi habis. Silakan ulangi proses.');
        }

        return view('auth.reset-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $userId = session('reset_user_id');
        if (!$userId) return redirect()->route('login');

        $user = User::findOrFail($userId);

        $user->password_hash = Hash::make($request->password);
        $user->save();

        // Hapus sesi
        $request->session()->forget(['reset_user_id', 'reset_verified']);

        return redirect()
            ->route('login')
            ->with('success', 'Password berhasil diubah. Silakan login.');
    }

    /* ============================================================
     * REGISTER
     * ============================================================ */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Normalisasi nomor telepon
        $cleanPhone = preg_replace('/\D/', '', $request->phone_number);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        } elseif (!str_starts_with($cleanPhone, '62')) {
            $cleanPhone = '62' . $cleanPhone;
        }

        $request->merge(['phone_number' => $cleanPhone]);

        $request->validate([
            'full_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'username'     => 'required|string|unique:users,username',
            'phone_number' => 'required|string|unique:users,phone_number',
            'password'     => 'required|string|min:8|confirmed',
        ], [
            'email.unique'        => 'Email ini sudah terdaftar.',
            'username.unique'     => 'Username ini sudah digunakan.',
            'phone_number.unique' => 'Nomor WhatsApp sudah terdaftar.',
            'password.min'        => 'Password minimal 8 karakter.',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'full_name'     => $request->full_name,
                'email'         => $request->email,
                'username'      => $request->username,
                'phone_number'  => $request->phone_number,
                'password_hash' => Hash::make($request->password),
                'role'          => 'user',
                'status'        => 'awaiting_otp',
            ]);

            $this->generateAndSendOtp($user, 'register');

            DB::commit();

            session([
                'temp_user_id' => $user->id,
                'otp_purpose'  => 'register'
            ]);

            return redirect()
                ->route('otp.form')
                ->with('success', 'Registrasi berhasil. Cek WhatsApp.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: '.$e->getMessage())->withInput();
        }
    }

    /* ============================================================
     * LOGIN
     * ============================================================ */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('username', $request->login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            return back()->with('error', 'Kredensial tidak valid.');
        }

        // Belum verifikasi registrasi
        if ($user->status == 'awaiting_otp') {
            $this->generateAndSendOtp($user, 'register');

            session([
                'temp_user_id' => $user->id,
                'otp_purpose'  => 'register'
            ]);

            return redirect()
                ->route('otp.form')
                ->with('warning', 'Selesaikan verifikasi pendaftaran.');
        }

        if ($user->status == 'pending_admin_review') {
            return back()->with('warning', 'Akun sedang ditinjau admin.');
        }

        if ($user->status == 'rejected') {
            return back()->with('error', 'Akun ditolak.');
        }

        // Kirim OTP login
        $this->generateAndSendOtp($user, 'login');

        session([
            'temp_user_id' => $user->id,
            'otp_purpose'  => 'login'
        ]);

        return redirect()->route('otp.form');
    }

    /* ============================================================
     * LOGIN OTP
     * ============================================================ */
    public function showOtpForm()
    {
        if (!session('temp_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp_code' => 'required|numeric']);

        $userId  = session('temp_user_id');
        $purpose = session('otp_purpose');

        if (!$userId) return redirect()->route('login');

        $otpRecord = OtpCode::where('user_id', $userId)
            ->where('code', $request->otp_code)
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return back()->with('error', 'Kode OTP salah/kadaluarsa.');
        }

        $otpRecord->update(['consumed_at' => Carbon::now()]);

        $user = User::find($userId);

        session()->forget(['temp_user_id', 'otp_purpose']);

        if ($purpose === 'register') {
            $user->update(['status' => 'pending_admin_review']);
            return redirect()->route('login')->with('success', 'Verifikasi berhasil. Menunggu persetujuan admin.');
        }

        if ($purpose === 'login') {
            Auth::login($user);
            $user->update(['last_login_at' => Carbon::now()]);

            return redirect()->route(
                $user->role === 'admin'
                ? 'admin.dashboard'
                : 'user.dashboard'
            );
        }
    }

    public function resendOtp()
    {
        $userId  = session('temp_user_id');
        $purpose = session('otp_purpose');

        if (!$userId) return redirect()->route('login');

        $this->generateAndSendOtp(User::find($userId), $purpose);

        return back()->with('success', 'OTP dikirim ulang.');
    }

    /* ============================================================
     * LOGOUT
     * ============================================================ */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
