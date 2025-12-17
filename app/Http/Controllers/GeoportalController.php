<?php

namespace App\Http\Controllers;

use App\Models\Geoportal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeoportalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Admin lihat semua, User lihat punya sendiri
        if ($user->role === 'admin') {
            $data = Geoportal::with('user')->latest()->get();
        } else {
            $data = Geoportal::where('user_id', $user->id)->latest()->get();
        }

        return view('geoportal.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'coordinates' => 'required', // String JSON
            'fixed_luas' => 'required|string', // Validasi Luas Wajib
        ]);

        // Proses Data Fluid (Key-Value)
        $properties = $this->processProperties($request);

        // TAMBAHAN: Masukkan Luas Lahan ke Properties
        $properties['Luas Lahan'] = $request->fixed_luas;

        Geoportal::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'type' => 'polygon',
            'coordinates' => json_decode($request->coordinates),
            'properties' => $properties,
        ]);

        return back()->with('success', 'Data spasial berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $geo = Geoportal::findOrFail($id);

        if (Auth::user()->role !== 'admin' && $geo->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'coordinates' => 'required',
            'fixed_luas' => 'required|string',
        ]);

        $properties = $this->processProperties($request);

        // TAMBAHAN: Update Luas Lahan
        $properties['Luas Lahan'] = $request->fixed_luas;

        $geo->update([
            'title' => $request->title,
            'description' => $request->description,
            'coordinates' => json_decode($request->coordinates),
            'properties' => $properties,
        ]);

        return back()->with('success', 'Data spasial berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $geo = Geoportal::findOrFail($id);
        if (Auth::user()->role !== 'admin' && $geo->user_id !== Auth::id()) {
            abort(403);
        }
        $geo->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }

    private function processProperties($request)
    {
        $properties = [];
        if ($request->has('keys') && $request->has('values')) {
            foreach ($request->keys as $index => $key) {
                if (!empty($key) && isset($request->values[$index])) {
                    $properties[$key] = $request->values[$index];
                }
            }
        }
        return $properties;
    }
}
