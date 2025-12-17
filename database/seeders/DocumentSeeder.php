<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentField;
use App\Models\DocumentSubfield;
use Illuminate\Support\Facades\DB;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        // Reset tabel master dokumen agar bersih saat di-seed ulang
        // Disable foreign key check sementara untuk truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DocumentSubfield::truncate();
        DocumentField::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Persyaratan Dokumen Lingkungan dan Pelaporannya
        $field1 = DocumentField::create([
            'code' => 'A',
            'name' => 'Persyaratan Dokumen Lingkungan',
            'description' => 'Dokumen legalitas lingkungan dasar perusahaan.',
            'sort_order' => 1,
        ]);

        DocumentSubfield::create([
            'field_id' => $field1->id,
            'name' => 'Dokumen Analisis Mengenai Dampak Lingkungan (AMDAL)',
            'description' => 'Dokumen AMDAL lengkap yang telah disetujui.',
            'required' => true,
            'max_size_mb' => 50, // AMDAL biasanya besar
            'sort_order' => 1,
        ]);
        DocumentSubfield::create([
            'field_id' => $field1->id,
            'name' => 'Dokumen Pengelolaan dan Pemantauan (UKL/UPL)',
            'description' => 'Dokumen Upaya Pengelolaan Lingkungan dan Upaya Pemantauan Lingkungan.',
            'required' => true,
            'max_size_mb' => 30,
            'sort_order' => 2,
        ]);

        // 2. Pengendalian Pencemaran Air
        $field2 = DocumentField::create([
            'code' => 'B',
            'name' => 'Pengendalian Pencemaran Air',
            'description' => 'Dokumen terkait izin dan teknis pembuangan air limbah.',
            'sort_order' => 2,
        ]);

        DocumentSubfield::create([
            'field_id' => $field2->id,
            'name' => 'Dokumen Pengendalian Pencemaran Air',
            'description' => 'Izin pembuangan air limbah (IPAL) dan dokumen teknis terkait.',
            'required' => true,
            'max_size_mb' => 20,
            'sort_order' => 1,
        ]);

        // 3. Pengendalian Pencemaran Udara
        $field3 = DocumentField::create([
            'code' => 'C',
            'name' => 'Pengendalian Pencemaran Udara',
            'description' => 'Dokumen terkait emisi udara dan pengelolaan kualitas udara.',
            'sort_order' => 3,
        ]);

        DocumentSubfield::create([
            'field_id' => $field3->id,
            'name' => 'Dokumen Pengendalian Pencemaran Udara',
            'description' => 'Persetujuan Teknis (Pertek) Emisi dan dokumen pendukung.',
            'required' => true,
            'max_size_mb' => 20,
            'sort_order' => 1,
        ]);

        // 4. Pengelolaan Limbah B3
        $field4 = DocumentField::create([
            'code' => 'D',
            'name' => 'Pengelolaan Limbah B3',
            'description' => 'Dokumen terkait pengelolaan Bahan Berbahaya dan Beracun.',
            'sort_order' => 4,
        ]);

        DocumentSubfield::create([
            'field_id' => $field4->id,
            'name' => 'Dokumen Pengelolaan Limbah B3',
            'description' => 'Izin TPS B3, manifest limbah, dan kontrak dengan pihak ketiga.',
            'required' => true,
            'max_size_mb' => 25,
            'sort_order' => 1,
        ]);

        // 5. Pengendalian Pencemaran Air Laut
        $field5 = DocumentField::create([
            'code' => 'E',
            'name' => 'Pengendalian Pencemaran Air Laut',
            'description' => 'Khusus untuk pembuangan ke laut (jika berlaku).',
            'sort_order' => 5,
        ]);

        DocumentSubfield::create([
            'field_id' => $field5->id,
            'name' => 'Dokumen Pengendalian Pencemaran Air Laut',
            'description' => 'Izin pembuangan air limbah ke laut.',
            'required' => true,
            'max_size_mb' => 20,
            'sort_order' => 1,
        ]);
        DocumentSubfield::create([
            'field_id' => $field5->id,
            'name' => 'Dokumen Ketaatan Pelaksanaan Pembuangan',
            'description' => 'Laporan hasil pemantauan kualitas air laut.',
            'required' => true,
            'max_size_mb' => 15,
            'sort_order' => 2,
        ]);

        // 6. Potensi Kerusakan Alam
        $field6 = DocumentField::create([
            'code' => 'F',
            'name' => 'Potensi Kerusakan Alam',
            'description' => 'Dokumen terkait pemulihan lahan atau kerusakan ekosistem.',
            'sort_order' => 6,
        ]);

        DocumentSubfield::create([
            'field_id' => $field6->id,
            'name' => 'Dokumen Potensi Kerusakan Alam',
            'description' => 'Analisis risiko atau dokumen pemulihan fungsi lingkungan hidup.',
            'required' => true,
            'max_size_mb' => 20,
            'sort_order' => 1,
        ]);

        // 7. BEYOND COMPLIANCE (Full Custom)
        // Tidak ada subfield statis yang dibuat di sini.
        // User akan menggunakan tombol "Tambah Dokumen Lainnya" untuk mengisi kategori ini.
        DocumentField::create([
            'code' => 'G',
            'name' => 'BEYOND COMPLIANCE',
            'description' => 'Inovasi sosial, keanekaragaman hayati, efisiensi energi, dan program unggulan lainnya.',
            'sort_order' => 7,
        ]);
    }
}
