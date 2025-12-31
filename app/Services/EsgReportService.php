<?php

namespace App\Services;

use App\Models\EsgReport;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\SimpleType\Jc;
use Dompdf\Dompdf;
use Dompdf\Options;

class EsgReportService
{
    protected GeminiService $gemini;

    /**
     * ESG Report Structure Template
     * Based on the provided ESG template
     */
    protected array $reportStructure = [
        1 => [
            'title' => 'Ringkasan Eksekutif (Executive Summary)',
            'subchapters' => [
                '1.1' => [
                    'title' => 'Sorotan Kinerja ESG (ESG Performance Highlights)',
                    'function' => 'Menyajikan rangkuman pencapaian dan tantangan utama ESG selama periode pelaporan.',
                    'placeholders' => ['Pencapaian Kunci E, S, G', 'Peringkat PROPER Tahun Ini', 'Target yang Tercapai'],
                    'sources' => ['Laporan Final', 'Data Kinerja E/S/G', 'Sertifikat PROPER'],
                ],
                '1.2' => [
                    'title' => 'Pernyataan CEO/Direksi (CEO/Board Statement)',
                    'function' => 'Menegaskan komitmen manajemen puncak terhadap keberlanjutan.',
                    'placeholders' => ['Pernyataan Komitmen CEO', 'Arah Strategis Keberlanjutan'],
                    'sources' => ['Dokumen Pernyataan Direksi', 'Rencana Strategis Perusahaan'],
                ],
            ],
        ],
        2 => [
            'title' => 'Profil Perusahaan & Tinjauan Keberlanjutan (Company Profile & Sustainability Overview)',
            'subchapters' => [
                '2.1' => [
                    'title' => 'Profil dan Struktur Tata Kelola',
                    'function' => 'Menyediakan informasi dasar perusahaan, model bisnis, dan struktur kepemimpinan.',
                    'placeholders' => ['Nama Perusahaan', 'Lokasi Operasi Utama', 'Struktur Organisasi'],
                    'sources' => ['Profil Perusahaan', 'Akta Pendirian', 'Laporan Tahunan'],
                ],
                '2.2' => [
                    'title' => 'Pendekatan Keberlanjutan dan Materialitas',
                    'function' => 'Menjelaskan visi, strategi, dan hasil asesmen materialitas ESG.',
                    'placeholders' => ['Visi dan Misi Keberlanjutan', 'Daftar Topik Material', 'Peta Pemangku Kepentingan'],
                    'sources' => ['Kebijakan Keberlanjutan', 'Hasil Asesmen Materialitas', 'Laporan Dialog Pemangku Kepentingan'],
                ],
                '2.3' => [
                    'title' => 'Batasan dan Periode Laporan',
                    'function' => 'Mendefinisikan cakupan dan periode data yang dilaporkan (sesuai GRI 1).',
                    'placeholders' => ['Periode Pelaporan', 'Batasan Organisasi dan Topik'],
                    'sources' => ['Laporan Tahun Sebelumnya', 'Catatan Metodologi Pelaporan'],
                ],
            ],
        ],
        3 => [
            'title' => 'Lingkungan (Environmental - E)',
            'subchapters' => [
                '3.1' => [
                    'title' => 'Tata Kelola Lingkungan dan Kepatuhan',
                    'function' => 'Menjelaskan kebijakan, sistem manajemen, dan tingkat kepatuhan terhadap regulasi (termasuk PROPER).',
                    'placeholders' => ['Ringkasan Kebijakan Lingkungan', 'Sistem Manajemen Lingkungan (ISO 14001)', 'Status Kepatuhan PROPER'],
                    'sources' => ['SOP Lingkungan', 'Sertifikat ISO 14001', 'Laporan Semester PROPER'],
                ],
                '3.2' => [
                    'title' => 'Energi dan Perubahan Iklim',
                    'function' => 'Melaporkan konsumsi energi, efisiensi, dan emisi Gas Rumah Kaca (GRK).',
                    'placeholders' => ['Total Konsumsi Energi (MWh)', 'Intensitas Energi', 'Emisi GRK Scope 1, 2, 3 (tCO2e)', 'Inisiatif Efisiensi Energi'],
                    'sources' => ['Laporan Energi', 'Data Pembelian Listrik', 'Laporan Emisi (CEMS/Manual)', 'Dokumen Beyond Compliance (Inovasi Energi)'],
                ],
                '3.3' => [
                    'title' => 'Air dan Efluen',
                    'function' => 'Melaporkan penggunaan air, efisiensi, dan pengelolaan air limbah.',
                    'placeholders' => ['Total Pengambilan Air (m³)', 'Intensitas Air', 'Volume Air Limbah Terolah', 'Kualitas Efluen (Sesuai Baku Mutu)'],
                    'sources' => ['Laporan Air', 'Data Penggunaan Air', 'Laporan Pengelolaan Air Limbah', 'Izin Pembuangan Limbah Cair'],
                ],
                '3.4' => [
                    'title' => 'Limbah (B3 dan Non-B3)',
                    'function' => 'Melaporkan volume, jenis, dan metode pengelolaan limbah B3 dan non-B3.',
                    'placeholders' => ['Total Limbah B3 Dihasilkan (ton)', 'Metode Pengelolaan Limbah B3', 'Total Limbah Non-B3 Dihasilkan (ton)', 'Inisiatif Circular Economy'],
                    'sources' => ['Logbook Limbah B3', 'Neraca Limbah', 'Manifest Limbah', 'Dokumen Beyond Compliance (Circular Economy)'],
                ],
                '3.5' => [
                    'title' => 'Keanekaragaman Hayati dan AMDAL',
                    'function' => 'Melaporkan dampak operasi terhadap keanekaragaman hayati dan pelaksanaan dokumen lingkungan.',
                    'placeholders' => ['Lokasi Operasi di Area Konservasi', 'Pelaksanaan RKL-RPL', 'Inisiatif Konservasi Flora/Fauna'],
                    'sources' => ['Dokumen AMDAL/UKL-UPL', 'Laporan Pelaksanaan RKL-RPL', 'Laporan Pemantauan Lingkungan'],
                ],
            ],
        ],
        4 => [
            'title' => 'Sosial (Social - S)',
            'subchapters' => [
                '4.1' => [
                    'title' => 'Ketenagakerjaan dan Kesetaraan',
                    'function' => 'Melaporkan praktik ketenagakerjaan, keragaman, dan kesetaraan kesempatan.',
                    'placeholders' => ['Jumlah Karyawan (Berdasarkan Gender/Usia)', 'Rasio Gaji Dasar Gender', 'Tingkat Turnover Karyawan'],
                    'sources' => ['Data HRD', 'Kebijakan Ketenagakerjaan', 'Laporan Audit SDM'],
                ],
                '4.2' => [
                    'title' => 'Kesehatan dan Keselamatan Kerja (K3)',
                    'function' => 'Melaporkan kinerja K3, pencegahan kecelakaan, dan promosi kesehatan.',
                    'placeholders' => ['Tingkat Kecelakaan Kerja (LTIFR/FR)', 'Jam Pelatihan K3', 'Sistem Manajemen K3 (SMK3/ISO 45001)'],
                    'sources' => ['Laporan K3 (P2K3)', 'Data Kecelakaan Kerja', 'Sertifikat SMK3/ISO 45001'],
                ],
                '4.3' => [
                    'title' => 'Pengembangan Masyarakat (Community Development - CSR)',
                    'function' => 'Melaporkan program dan investasi sosial, termasuk aspek PROPER Comdev.',
                    'placeholders' => ['Total Investasi Sosial (Rp)', 'Fokus Program CSR', 'Dampak Program Terhadap Masyarakat'],
                    'sources' => ['Laporan CSR', 'Dokumen Beyond Compliance (Comdev PROPER)', 'Laporan Dampak Sosial'],
                ],
                '4.4' => [
                    'title' => 'Rantai Pasok dan Hak Asasi Manusia',
                    'function' => 'Melaporkan asesmen risiko HAM dan praktik keberlanjutan pemasok.',
                    'placeholders' => ['Kebijakan HAM', 'Persentase Pemasok yang Diaudit ESG', 'Hasil Asesmen Risiko Rantai Pasok'],
                    'sources' => ['Kode Etik Pemasok', 'Hasil Audit Pemasok', 'Kebijakan HAM Perusahaan'],
                ],
            ],
        ],
        5 => [
            'title' => 'Tata Kelola (Governance - G)',
            'subchapters' => [
                '5.1' => [
                    'title' => 'Struktur dan Komposisi Tata Kelola',
                    'function' => 'Menjelaskan peran dan tanggung jawab organ tata kelola dalam isu ESG.',
                    'placeholders' => ['Komposisi Dewan Komisaris/Direksi', 'Keterlibatan Dewan dalam Isu ESG', 'Mekanisme Pengawasan ESG'],
                    'sources' => ['Piagam Dewan (Board Charter)', 'Profil Anggota Dewan', 'Kebijakan GCG'],
                ],
                '5.2' => [
                    'title' => 'Etika Bisnis dan Anti-Korupsi',
                    'function' => 'Melaporkan kebijakan anti-korupsi, pelatihan, dan mekanisme pelaporan pelanggaran (Whistleblowing).',
                    'placeholders' => ['Kebijakan Anti-Korupsi', 'Jumlah Pelatihan Etika', 'Jumlah Laporan Whistleblowing Diterima/Ditindaklanjuti'],
                    'sources' => ['Kode Etik Perusahaan', 'Laporan Pelatihan', 'Laporan Komite Audit'],
                ],
                '5.3' => [
                    'title' => 'Manajemen Risiko ESG',
                    'function' => 'Menjelaskan proses identifikasi, asesmen, dan mitigasi risiko ESG (termasuk risiko iklim TCFD).',
                    'placeholders' => ['Proses Identifikasi Risiko ESG', 'Daftar Risiko Material ESG', 'Strategi Mitigasi Risiko Iklim'],
                    'sources' => ['Risk Register Perusahaan', 'Laporan TCFD (jika ada)', 'Kebijakan Manajemen Risiko'],
                ],
            ],
        ],
        6 => [
            'title' => 'Ringkasan Kinerja ESG & Keselarasan (ESG Performance Summary & Alignment)',
            'subchapters' => [
                '6.1' => [
                    'title' => 'Indeks Kinerja Utama (KPI)',
                    'function' => 'Menyajikan data kinerja ESG dalam format tabel komparatif (3-5 tahun).',
                    'placeholders' => ['Tabel Data Kinerja E, S, G (3-5 Tahun)', 'Perbandingan dengan Target'],
                    'sources' => ['Data Kinerja Historis', 'Laporan Tahun Sebelumnya'],
                ],
                '6.2' => [
                    'title' => 'Keselarasan dengan Standar dan SDGs',
                    'function' => 'Memetakan kinerja perusahaan terhadap GRI, PROPER, dan Tujuan Pembangunan Berkelanjutan (SDGs).',
                    'placeholders' => ['Tabel Indeks Konten GRI', 'Tabel Peta Kontribusi SDGs', 'Peta Keselarasan PROPER Beyond Compliance'],
                    'sources' => ['Laporan Final', 'Indeks Konten GRI', 'Dokumen Beyond Compliance'],
                ],
            ],
        ],
        7 => [
            'title' => 'Kesimpulan & Komitmen Masa Depan (Conclusion & Future Commitment)',
            'subchapters' => [
                '7.1' => [
                    'title' => 'Pelajaran dan Pembelajaran',
                    'function' => 'Merangkum pembelajaran utama dari periode pelaporan.',
                    'placeholders' => ['Refleksi Kinerja', 'Tantangan yang Dihadapi'],
                    'sources' => ['Laporan Final', 'Analisis Kinerja'],
                ],
                '7.2' => [
                    'title' => 'Target dan Komitmen',
                    'function' => 'Menyatakan target ESG yang akan datang dan komitmen jangka panjang.',
                    'placeholders' => ['Target Jangka Pendek E, S, G', 'Komitmen Net Zero/Keberlanjutan Jangka Panjang'],
                    'sources' => ['Rencana Kerja Perusahaan', 'Kebijakan Jangka Panjang'],
                ],
            ],
        ],
    ];

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    /**
     * Get the report structure for display
     */
    public function getReportStructure(): array
    {
        return $this->reportStructure;
    }

    /**
     * Count total subchapters
     */
    public function getTotalSubchapters(): int
    {
        $count = 0;
        foreach ($this->reportStructure as $chapter) {
            $count += count($chapter['subchapters']);
        }
        return $count;
    }

    /**
     * Generate the complete ESG report
     */
    public function generateReport(EsgReport $report): void
    {
        try {
            $report->markAsProcessing();

            $userId = $report->user_id;
            $totalChapters = count($this->reportStructure);
            $totalSubchapters = $this->getTotalSubchapters();

            // Get user's approved documents
            $documents = Document::where('user_id', $userId)
                ->where('status', 'approved')
                ->with('subfield')
                ->get();

            $documentPaths = $documents->pluck('storage_path')->toArray();
            $documentNames = $documents->map(fn($d) => $d->subfield?->name . ': ' . $d->original_filename)->toArray();

            $processedSubchapters = 0;

            // Generate content for each chapter
            foreach ($this->reportStructure as $chapterNum => $chapter) {
                foreach ($chapter['subchapters'] as $subKey => $subchapter) {
                    // Update progress
                    $processedSubchapters++;
                    $percentage = (int) (($processedSubchapters / $totalSubchapters) * 90); // Reserve 10% for file generation

                    $report->updateProgress(
                        $chapterNum,
                        $totalChapters,
                        "{$subKey}. {$subchapter['title']}",
                        $percentage
                    );

                    // Generate content using AI
                    $content = $this->generateSubchapterContent(
                        $chapterNum,
                        $chapter['title'],
                        $subKey,
                        $subchapter,
                        $documentPaths,
                        $documentNames
                    );

                    // Store the generated content
                    $report->appendChapterContent($subKey, $content);

                    // Small delay to prevent API rate limiting
                    usleep(500000); // 0.5 second
                }
            }

            // Update progress for file generation
            $report->updateProgress(7, 7, 'Membuat file dokumen...', 95);

            // Generate the document file
            $filePath = $this->generateDocumentFile($report);

            $report->markAsCompleted($filePath);

        } catch (\Exception $e) {
            Log::error('ESG Report Generation Failed: ' . $e->getMessage(), [
                'report_id' => $report->id,
                'trace' => $e->getTraceAsString(),
            ]);
            $report->markAsFailed($e->getMessage());
        }
    }

    /**
     * Generate content for a specific subchapter using AI
     */
    protected function generateSubchapterContent(
        int $chapterNum,
        string $chapterTitle,
        string $subKey,
        array $subchapter,
        array $documentPaths,
        array $documentNames
    ): string {
        $prompt = $this->buildSubchapterPrompt($chapterNum, $chapterTitle, $subKey, $subchapter, $documentNames);

        try {
            $response = $this->gemini->generateEsgChapter($prompt, $documentPaths);
            return $response;
        } catch (\Exception $e) {
            Log::warning("Failed to generate subchapter {$subKey}: " . $e->getMessage());
            return "**[Konten tidak dapat di-generate untuk bagian ini. Error: {$e->getMessage()}]**";
        }
    }

    /**
     * Build the prompt for AI generation
     */
    protected function buildSubchapterPrompt(
        int $chapterNum,
        string $chapterTitle,
        string $subKey,
        array $subchapter,
        array $documentNames
    ): string {
        $placeholders = implode(', ', $subchapter['placeholders']);
        $sources = implode(', ', $subchapter['sources']);
        $docList = !empty($documentNames) ? implode("\n- ", $documentNames) : 'Tidak ada dokumen yang tersedia';

        return <<<PROMPT
Anda adalah penulis laporan ESG (Environmental, Social, Governance) profesional.

TUGAS: Tuliskan bagian laporan ESG untuk:
- **Bab {$chapterNum}**: {$chapterTitle}
- **Sub-Bab {$subKey}**: {$subchapter['title']}

FUNGSI BAGIAN INI:
{$subchapter['function']}

INFORMASI YANG HARUS DICAKUP:
{$placeholders}

DOKUMEN SUMBER YANG RELEVAN:
{$sources}

DOKUMEN YANG TERSEDIA DARI USER:
- {$docList}

INSTRUKSI PENULISAN:
1. Gunakan Bahasa Indonesia formal dan profesional
2. Tulis dalam format naratif yang informatif (bukan bullet points saja)
3. Minimal 3 paragraf substantif
4. Jika data spesifik ditemukan di dokumen, gunakan data tersebut
5. Jika data tidak tersedia, jelaskan secara umum praktik terbaik industri dengan catatan "[Data perlu dilengkapi]"
6. Sertakan konteks dan penjelasan yang relevan
7. Gunakan format Markdown untuk struktur (heading, bold, italic jika perlu)

OUTPUT: Tulis konten lengkap untuk sub-bab ini.
PROMPT;
    }

    /**
     * Generate the final document file (DOCX or PDF)
     */
    protected function generateDocumentFile(EsgReport $report): string
    {
        $phpWord = new PhpWord();

        // Set default font
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        // Define styles
        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 16, 'color' => '1a365d'], ['spaceAfter' => 200]);
        $phpWord->addTitleStyle(2, ['bold' => true, 'size' => 13, 'color' => '2c5282'], ['spaceAfter' => 100]);

        // Create section with margins
        $section = $phpWord->addSection([
            'marginTop' => 1440,
            'marginBottom' => 1440,
            'marginLeft' => 1440,
            'marginRight' => 1440,
        ]);

        // Add cover page
        $section->addTextBreak(3);
        $section->addText(
            'LAPORAN ESG',
            ['bold' => true, 'size' => 28, 'color' => '1a365d'],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 200]
        );
        $section->addText(
            '(Environmental, Social, Governance)',
            ['bold' => true, 'size' => 14, 'color' => '4a5568'],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 400]
        );
        $section->addTextBreak(2);
        $section->addText(
            $report->title,
            ['bold' => true, 'size' => 14],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 200]
        );
        $section->addText(
            'Dibuat pada: ' . now()->format('d F Y'),
            ['size' => 11, 'color' => '718096'],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 400]
        );
        $section->addTextBreak(4);
        $section->addText(
            'Dokumen ini di-generate menggunakan AI Gemini',
            ['size' => 10, 'italic' => true, 'color' => '718096'],
            ['alignment' => Jc::CENTER]
        );

        // Page break after cover
        $section->addPageBreak();

        // Add content for each chapter
        $chapterContents = $report->chapter_content ?? [];

        foreach ($this->reportStructure as $chapterNum => $chapter) {
            // Chapter title
            $section->addTitle("BAB {$chapterNum}. " . strtoupper($chapter['title']), 1);

            foreach ($chapter['subchapters'] as $subKey => $subchapter) {
                // Subchapter title
                $section->addTitle("{$subKey}. {$subchapter['title']}", 2);

                // Get content
                $content = $chapterContents[$subKey] ?? '[Konten tidak tersedia]';

                // Parse markdown and add to document
                $this->addMarkdownContent($section, $content);

                $section->addTextBreak(1);
            }

            $section->addPageBreak();
        }

        // Generate file
        $filename = 'esg_report_' . $report->id . '_' . time();
        $docxPath = "esg_reports/{$filename}.docx";

        // Ensure directory exists
        Storage::disk('public')->makeDirectory('esg_reports');

        $fullDocxPath = Storage::disk('public')->path($docxPath);

        // Save DOCX
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($fullDocxPath);

        // Convert to PDF if requested
        if ($report->output_format === 'pdf') {
            $pdfPath = "esg_reports/{$filename}.pdf";
            $fullPdfPath = Storage::disk('public')->path($pdfPath);

            // Use dompdf for HTML to PDF conversion
            $this->convertDocxToPdf($phpWord, $fullPdfPath);

            return $pdfPath;
        }

        return $docxPath;
    }

    /**
     * Add markdown content to Word document section
     */
    protected function addMarkdownContent($section, string $content): void
    {
        // Simple markdown parsing
        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            // Check for headers
            if (preg_match('/^###\s+(.+)$/', $line, $matches)) {
                $section->addText($matches[1], ['bold' => true, 'size' => 11], ['spaceAfter' => 60]);
            } elseif (preg_match('/^##\s+(.+)$/', $line, $matches)) {
                $section->addText($matches[1], ['bold' => true, 'size' => 12], ['spaceAfter' => 80]);
            } elseif (preg_match('/^#\s+(.+)$/', $line, $matches)) {
                $section->addText($matches[1], ['bold' => true, 'size' => 14], ['spaceAfter' => 100]);
            } elseif (preg_match('/^[-*]\s+(.+)$/', $line, $matches)) {
                // Bullet point
                $section->addListItem($this->parseInlineMarkdown($matches[1]), 0);
            } elseif (preg_match('/^\d+\.\s+(.+)$/', $line, $matches)) {
                // Numbered list
                $section->addListItem($this->parseInlineMarkdown($matches[1]), 0);
            } else {
                // Regular paragraph
                $textRun = $section->addTextRun(['spaceAfter' => 120]);
                $this->addFormattedText($textRun, $line);
            }
        }
    }

    /**
     * Parse inline markdown (bold, italic)
     */
    protected function parseInlineMarkdown(string $text): string
    {
        // Remove markdown formatting for list items (simplified)
        $text = preg_replace('/\*\*(.+?)\*\*/', '$1', $text);
        $text = preg_replace('/\*(.+?)\*/', '$1', $text);
        return $text;
    }

    /**
     * Add formatted text with bold/italic support
     */
    protected function addFormattedText($textRun, string $text): void
    {
        // Simple implementation - just add plain text
        // For full markdown support, would need more complex parsing
        $text = preg_replace('/\*\*(.+?)\*\*/', '$1', $text);
        $text = preg_replace('/\*(.+?)\*/', '$1', $text);
        $textRun->addText($text, ['size' => 11]);
    }

    /**
     * Convert DOCX to PDF using dompdf
     */
    protected function convertDocxToPdf(PhpWord $phpWord, string $pdfPath): void
    {
        // Generate HTML from PhpWord
        $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
        $tempHtml = tempnam(sys_get_temp_dir(), 'esg_') . '.html';
        $htmlWriter->save($tempHtml);

        // Read HTML content
        $html = file_get_contents($tempHtml);

        // Configure dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Save PDF
        file_put_contents($pdfPath, $dompdf->output());

        // Cleanup temp file
        @unlink($tempHtml);
    }
}
