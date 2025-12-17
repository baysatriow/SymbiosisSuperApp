<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\UserProfile;
use App\Models\CompanyProfile;
use App\Models\OtpCode;
use App\Services\FonnteService;
use Carbon\Carbon;

class ProfileController extends Controller
{
    protected $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    /* ============================================================
     * USER PROFILE — EDIT
     * ============================================================ */
    public function editUser()
    {
        $user = Auth::user();
        $profile = $user->userProfile ?? new UserProfile();
        return view('user.profile', compact('user', 'profile'));
    }

    /* ============================================================
     * USER PROFILE — UPDATE BASIC INFO & ADDRESS
     * ============================================================ */
    public function updateUser(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name'       => 'required|string|max:255',
            'job_title'       => 'required|string|max:255',
            'job_sector'      => 'required|string|max:255',
            'company_address' => 'required|string',
            'province_id'     => 'required|string',
            'province_name'   => 'required|string',
            'city_id'         => 'required|string',
            'city_name'       => 'required|string',
            'postal_code'     => 'required|string|max:10',
        ]);

        DB::beginTransaction();
        try {
            // Update nama user
            $user->full_name = $request->full_name;
            $user->save();

            // Update atau buat UserProfile
            UserProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'job_title'        => $request->job_title,
                    'job_sector'       => $request->job_sector,
                    'company_address'  => $request->company_address,
                    'province_id'      => $request->province_id,
                    'province_name'    => $request->province_name,
                    'city_id'          => $request->city_id,
                    'city_name'        => $request->city_name,
                    'postal_code'      => $request->postal_code,
                    'additional_primary_fields' => [
                        'nik' => $request->nik,
                    ],
                    'is_completed'     => true,
                ]
            );

            DB::commit();
            return back()->with('success', 'Profil berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /* ============================================================
     * USER PROFILE — UPDATE PASSWORD
     * ============================================================ */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password_hash)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        $user->password_hash = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah.');
    }

    /* ============================================================
     * PHONE NUMBER — REQUEST OTP
     * ============================================================ */
    public function requestPhoneChangeOtp(Request $request)
    {
        $request->validate([
            'new_phone' => 'required|string|min:10|unique:users,phone_number'
        ]);

        // Normalisasi nomor HP
        $cleanPhone = preg_replace('/\D/', '', $request->new_phone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        } elseif (!str_starts_with($cleanPhone, '62')) {
            $cleanPhone = '62' . $cleanPhone;
        }

        $user = Auth::user();
        $code = rand(100000, 999999);

        // Simpan OTP
        OtpCode::create([
            'user_id'    => $user->id,
            'code'       => $code,
            'purpose'    => 'login',
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        session(['temp_new_phone' => $cleanPhone]);

        // Kirim ke nomor baru
        $this->fonnte->sendWhatsApp($cleanPhone, "Kode ganti nomor: *$code*");

        return response()->json([
            'status'  => 'success',
            'message' => 'OTP terkirim.'
        ]);
    }

    /* ============================================================
     * PHONE NUMBER — VERIFY OTP
     * ============================================================ */
    public function verifyPhoneChangeOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        $user     = Auth::user();
        $newPhone = session('temp_new_phone');

        if (!$newPhone) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Sesi habis.'
            ], 400);
        }

        $otp = OtpCode::where('user_id', $user->id)
            ->where('code', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->whereNull('consumed_at')
            ->latest()
            ->first();

        if ($otp) {
            $user->phone_number = $newPhone;
            $user->save();

            $otp->update(['consumed_at' => Carbon::now()]);

            session()->forget('temp_new_phone');

            return response()->json([
                'status'  => 'success',
                'message' => 'Nomor berhasil diubah!'
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'OTP salah.'
        ], 400);
    }

    /* ============================================================
     * COMPANY PROFILE — EDIT
     * ============================================================ */
    public function editCompany()
    {
        $user = Auth::user();
        $company = $user->companyProfile ?? new CompanyProfile();
        return view('user.company', compact('user', 'company'));
    }

    /* ============================================================
     * COMPANY PROFILE — UPDATE
     * ============================================================ */
    public function updateCompany(Request $request)
    {
        $request->validate([
            // Identitas
            'company_name'      => 'required|string|max:255',
            'legal_entity_type' => 'required|in:PT,CV,Fund,Org,UMKM,Koperasi,Yayasan',
            'tax_id_npwp'       => 'nullable|numeric',
            'nib_number'        => 'nullable|numeric',
            'year_founded'      => 'nullable|numeric|digits:4|max:' . (date('Y') + 1),

            // Kontak & Lokasi
            'address'        => 'required|string|max:500',
            'province_id'    => 'required|string',
            'province_name'  => 'required|string',
            'city_id'        => 'required|string',
            'city_name'      => 'required|string',
            'postal_code'    => 'required|numeric|digits:5',
            'contact_email'  => 'required|email',
            'contact_phone'  => 'required|string|min:8|max:15',
            'website'        => 'nullable|url',

            // Operasional
            'sector'         => 'required|string',
            'size_employees' => 'nullable|integer|min:1',
        ]);

        $user = Auth::user();

        CompanyProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'company_name'      => $request->company_name,
                'legal_entity_type' => $request->legal_entity_type,
                'nib_number'        => $request->nib_number,
                'siup_number'       => $request->siup_number,
                'tax_id_npwp'       => $request->tax_id_npwp,

                // Lokasi
                'address'        => $request->address,
                'province_id'    => $request->province_id,
                'province'       => $request->province_name,
                'city_id'        => $request->city_id,
                'city'           => $request->city_name,
                'country'        => 'Indonesia',
                'postal_code'    => $request->postal_code,

                // Kontak
                'website'        => $request->website,
                'contact_email'  => $request->contact_email,
                'contact_phone'  => $request->contact_phone,

                // Operasional
                'sector'         => $request->sector,
                'size_employees' => $request->size_employees,
                'year_founded'   => $request->year_founded,
                'description'    => $request->description,
                'is_completed'   => true,
            ]
        );

        return redirect()
            ->route('user.company')
            ->with('success', 'Profil perusahaan berhasil diperbarui.');
    }
}
