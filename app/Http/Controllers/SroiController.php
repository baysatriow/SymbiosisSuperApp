<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SroiController extends Controller
{
    /* ============================================================
     * SROI INDEX
     * ============================================================ */
    public function index()
    {
        $user = Auth::user();

        // Gatekeeper: pastikan profil user & perusahaan lengkap
        if (
            !$user->userProfile?->is_completed ||
            !$user->companyProfile?->is_completed
        ) {
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'Harap lengkapi profil Anda sebelum menggunakan SROI Calculator.');
        }

        return view('user.sroi.index');
    }
}
