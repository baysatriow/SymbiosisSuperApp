<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\DocumentField;
use App\Models\DocumentSubfield;
use App\Models\Document;

class MasterDocumentController extends Controller
{
    /* ============================================================
     * INDEX — LIST MASTER FIELD & SUBFIELD
     * ============================================================ */
    public function index()
    {
        // Ambil Field beserta Subfields + hitung jumlah dokumen per subfield
        $fields = DocumentField::with([
            'subfields' => function ($q) {
                $q->withCount('documents');
            }
        ])
        ->orderBy('sort_order')
        ->get();

        // Hitung statistik agregat per field
        foreach ($fields as $field) {
            $field->total_docs     = $field->subfields->sum('documents_count');
            $field->total_subfields = $field->subfields->count();
        }

        return view('admin.master_documents.index', compact('fields'));
    }

    /* ============================================================
     * FIELD CRUD
     * ============================================================ */
    public function storeField(Request $request)
    {
        $request->validate([
            'code'       => 'required|string|max:10|unique:document_fields,code',
            'name'       => 'required|string|max:255',
            'sort_order' => 'required|integer',
        ]);

        DocumentField::create($request->only(['code', 'name', 'sort_order']));

        return back()->with('success', 'Kategori dokumen berhasil ditambahkan.');
    }

    public function updateField(Request $request, $id)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'code'       => "required|string|max:10|unique:document_fields,code,$id",
            'sort_order' => 'required|integer',
        ]);

        DocumentField::findOrFail($id)->update(
            $request->only(['code', 'name', 'sort_order'])
        );

        return back()->with('success', 'Kategori dokumen diperbarui.');
    }

    public function destroyField($id)
    {
        $field = DocumentField::with('subfields.documents')->findOrFail($id);

        // Hapus file fisik dari setiap dokumen
        foreach ($field->subfields as $sub) {
            foreach ($sub->documents as $doc) {
                if (Storage::disk('public')->exists($doc->storage_path)) {
                    Storage::disk('public')->delete($doc->storage_path);
                }
            }
        }

        // Hapus field → cascade/hard delete tergantung migration
        $field->delete();

        return back()->with('success', 'Kategori dan seluruh isinya berhasil dihapus.');
    }

    /* ============================================================
     * SUBFIELD CRUD
     * ============================================================ */
    public function storeSubfield(Request $request)
    {
        $request->validate([
            'field_id'    => 'required|exists:document_fields,id',
            'name'        => 'required|string|max:255',
            'max_size_mb' => 'required|integer|min:1',
        ]);

        DocumentSubfield::create([
            'field_id'    => $request->field_id,
            'name'        => $request->name,
            'description' => $request->description,
            'required'    => $request->has('required'),
            'max_size_mb' => $request->max_size_mb,
            'is_custom'   => false, // Admin → standard
        ]);

        return back()->with('success', 'Jenis dokumen berhasil ditambahkan.');
    }

    public function updateSubfield(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'max_size_mb' => 'required|integer|min:1',
        ]);

        $sub = DocumentSubfield::findOrFail($id);

        $sub->update([
            'name'        => $request->name,
            'description' => $request->description,
            'required'    => $request->has('required'),
            'max_size_mb' => $request->max_size_mb,
        ]);

        return back()->with('success', 'Jenis dokumen diperbarui.');
    }

    /* ============================================================
     * DELETE SUBFIELD + CLEAN ALL DOCUMENTS
     * ============================================================ */
    public function destroySubfield($id)
    {
        $sub = DocumentSubfield::with('documents')->findOrFail($id);

        // Hapus semua dokumen fisik + record dokumen
        foreach ($sub->documents as $doc) {
            if ($doc->storage_path && Storage::disk('public')->exists($doc->storage_path)) {
                Storage::disk('public')->delete($doc->storage_path);
            }

            // Penting: hapus dokumen terlebih dahulu (hindari RESTRICT constraint)
            $doc->delete();
        }

        // Hapus subfield setelah dokumen bersih
        $sub->delete();

        return back()->with('success', 'Jenis dokumen dan file terkait berhasil dihapus.');
    }
}
