<x-app-layout>
    <!-- Load Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- ExcelJS untuk membuat file .xlsx dengan style & gambar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.4.0/exceljs.min.js"></script>
    <!-- FileSaver untuk menyimpan file -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <style>
        @media print {
            nav, aside, button, .no-print, a[href] { display: none !important; }
            body, .p-4, .sm\:ml-64, .mt-14 { margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: white !important; }
            .max-w-7xl { max-width: 100% !important; }
            .grid { display: block !important; }
            .lg\:col-span-2, .lg\:col-span-1 { width: 100% !important; margin-bottom: 20px !important; }
            input, select, textarea { border: none !important; background: transparent !important; padding: 0 !important; font-weight: bold; color: black !important; box-shadow: none !important; appearance: none; }
            .overflow-x-auto { overflow: visible !important; }
            canvas { max-width: 100% !important; max-height: 300px !important; page-break-inside: avoid; }
            .print-header { display: block !important; text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
            .absolute.inset-y-0 { display: none !important; }
            input.ps-10 { padding-left: 0 !important; }
        }
        .print-header { display: none; }
    </style>

    <div class="max-w-7xl mx-auto">

        <div class="print-header">
            <h1 class="text-2xl font-bold uppercase">Laporan Analisis SROI</h1>
            <p class="text-sm text-gray-600">{{ config('app.name', 'Symbiosis') }} - {{ date('d M Y') }}</p>
            <p class="text-sm text-gray-600">Oleh: {{ Auth::user()->full_name }}</p>
        </div>

        <div class="mb-6 no-print">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">SROI Calculator</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Hitung dampak sosial investasi Anda.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- KOLOM KIRI -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Card 1: Parameter -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b pb-2 border-gray-200 dark:border-gray-700">1. Parameter Investasi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total Investasi (IDR)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none no-print"><span class="text-gray-500 dark:text-gray-400 text-sm">Rp</span></div>
                                <input type="number" id="total_investment" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white font-bold" value="100000000">
                            </div>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Discount Rate (%)</label>
                            <input type="number" id="discount_rate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white font-bold" value="3.5" step="0.1">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Durasi Proyek (Tahun)</label>
                            <select id="time_horizon" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white font-bold">
                                <option value="3">3 Tahun</option>
                                <option value="5" selected>5 Tahun</option>
                                <option value="10">10 Tahun</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Outcomes -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4 border-b pb-2 border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">2. Analisis Dampak</h3>
                        <button type="button" onclick="addOutcomeRow()" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-xs px-3 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 no-print flex gap-1 items-center">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg> Tambah
                        </button>
                    </div>
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="outcomes_table">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-2 py-2 min-w-[150px]">Nama Dampak</th>
                                    <th class="px-2 py-2 min-w-[120px]">Nilai (Rp)</th>
                                    <th class="px-1 py-2 text-center text-[10px]">Deadweight %</th>
                                    <th class="px-1 py-2 text-center text-[10px]">Attribution %</th>
                                    <th class="px-1 py-2 text-center text-[10px]">Displacement %</th>
                                    <th class="px-1 py-2 text-center text-[10px]">Drop-off %</th>
                                    <th class="px-2 py-2 text-center no-print">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="outcomes_body">
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 outcome-row">
                                    <td class="px-2 py-2"><input type="text" class="bg-gray-50 border border-gray-300 text-xs rounded block w-full p-2 dark:bg-gray-700 dark:text-white outcome-name" value="Peningkatan Pendapatan Mitra"></td>
                                    <td class="px-2 py-2"><input type="number" class="bg-gray-50 border border-gray-300 text-xs rounded block w-full p-2 dark:bg-gray-700 dark:text-white outcome-value" value="50000000"></td>
                                    <td class="px-1 py-2"><input type="number" class="bg-gray-50 border border-gray-300 text-xs rounded block w-full p-2 text-center dark:bg-gray-700 dark:text-white outcome-deadweight" value="10"></td>
                                    <td class="px-1 py-2"><input type="number" class="bg-gray-50 border border-gray-300 text-xs rounded block w-full p-2 text-center dark:bg-gray-700 dark:text-white outcome-attribution" value="20"></td>
                                    <td class="px-1 py-2"><input type="number" class="bg-gray-50 border border-gray-300 text-xs rounded block w-full p-2 text-center dark:bg-gray-700 dark:text-white outcome-displacement" value="5"></td>
                                    <td class="px-1 py-2"><input type="number" class="bg-gray-50 border border-gray-300 text-xs rounded block w-full p-2 text-center dark:bg-gray-700 dark:text-white outcome-dropoff" value="15"></td>
                                    <td class="px-2 py-2 text-center no-print"><button onclick="removeRow(this)" class="text-red-600 hover:text-red-900">Hapus</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 no-print">
                        <button type="button" onclick="calculateSROI()" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-3 text-center shadow-lg hover:-translate-y-0.5 transform transition">HITUNG ULANG</button>
                    </div>
                </div>

                <!-- MENU 3: EKSPOR LAPORAN -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 no-print">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        3. Ekspor Laporan
                    </h3>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" onclick="exportToExcel()" class="flex-1 text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center justify-center gap-2 transition shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Download Laporan Excel (.xlsx)
                        </button>
                        <button type="button" onclick="window.print()" class="flex-1 text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center justify-center gap-2 transition shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak PDF
                        </button>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: HASIL -->
            <div class="lg:col-span-1 space-y-6">
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700 relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-green-100 dark:bg-green-900 opacity-50 blur-xl"></div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 relative z-10">Ringkasan Hasil</h3>
                    <div class="flex flex-col items-center justify-center py-6 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400 mb-1">Rasio SROI</span>
                        <div class="text-5xl font-extrabold text-green-600 dark:text-green-400" id="result_ratio">0 : 1</div>
                        <span class="text-xs text-gray-400 mt-2 text-center px-4">Perbandingan Investasi vs Dampak</span>
                    </div>
                    <div class="mt-4 space-y-3">
                        <div class="flex justify-between"><span class="text-sm text-gray-500">Total PV</span><span class="text-sm font-bold text-gray-900 dark:text-white" id="result_pv">Rp 0</span></div>
                        <div class="flex justify-between"><span class="text-sm text-gray-500">NPV</span><span class="text-sm font-bold text-gray-900 dark:text-white" id="result_npv">Rp 0</span></div>
                        <div class="flex justify-between"><span class="text-sm text-gray-500">Investasi</span><span class="text-sm font-bold text-gray-900 dark:text-white" id="result_investment">Rp 0</span></div>
                    </div>
                </div>
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Grafik Proyeksi</h3>
                    <div class="relative h-64 w-full bg-white rounded">
                        <canvas id="sroiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let sroiChart = null;
        const formatRupiah = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n);

        function addOutcomeRow() {
            const tbody = document.getElementById('outcomes_body');
            const row = document.createElement('tr');
            row.className = 'bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 outcome-row';
            row.innerHTML = `
                <td class="px-2 py-2"><input type="text" class="bg-gray-50 border border-gray-300 text-xs rounded block w-full p-2 dark:bg-gray-700 dark:text-white outcome-name" placeholder="Nama Dampak"></td>
                <td class="px-2 py-2"><input type="number" class="bg-gray-50 border border-gray-300 text-xs rounded block w-full p-2 dark:bg-gray-700 dark:text-white outcome-value" value="0"></td>
                <td class="px-1 py-2"><input type="number" class="bg-gray-50 border border-gray-300 text-xs rounded block w-full p-2 text-center dark:bg-gray-700 dark:text-white outcome-deadweight" value="0"></td>
                <td class="px-1 py-2"><input type="number" class="bg-gray-50 border border-gray-300 text-xs rounded block w-full p-2 text-center dark:bg-gray-700 dark:text-white outcome-attribution" value="0"></td>
                <td class="px-1 py-2"><input type="number" class="bg-gray-50 border border-gray-300 text-xs rounded block w-full p-2 text-center dark:bg-gray-700 dark:text-white outcome-displacement" value="0"></td>
                <td class="px-1 py-2"><input type="number" class="bg-gray-50 border border-gray-300 text-xs rounded block w-full p-2 text-center dark:bg-gray-700 dark:text-white outcome-dropoff" value="0"></td>
                <td class="px-2 py-2 text-center no-print"><button onclick="removeRow(this)" class="text-red-600 hover:text-red-900">Hapus</button></td>
            `;
            tbody.appendChild(row);
        }

        function removeRow(btn) {
            if(document.querySelectorAll('.outcome-row').length > 1) { btn.closest('tr').remove(); calculateSROI(); }
            else { alert("Minimal satu dampak diperlukan."); }
        }

        function calculateSROI() {
            const investment = parseFloat(document.getElementById('total_investment').value) || 0;
            const discountRate = parseFloat(document.getElementById('discount_rate').value) || 0;
            const years = parseInt(document.getElementById('time_horizon').value) || 5;

            const rows = document.querySelectorAll('.outcome-row');
            let yearlyPVs = new Array(years).fill(0);
            let totalPV = 0;

            rows.forEach(row => {
                const value = parseFloat(row.querySelector('.outcome-value').value) || 0;
                const dw = parseFloat(row.querySelector('.outcome-deadweight').value)/100 || 0;
                const att = parseFloat(row.querySelector('.outcome-attribution').value)/100 || 0;
                const disp = parseFloat(row.querySelector('.outcome-displacement').value)/100 || 0;
                const drop = parseFloat(row.querySelector('.outcome-dropoff').value)/100 || 0;

                let impact = value * (1 - dw) * (1 - att) * (1 - disp);

                for (let t = 1; t <= years; t++) {
                    if (t > 1) impact = impact * (1 - drop);
                    const pv = impact / Math.pow(1 + (discountRate / 100), t);
                    yearlyPVs[t-1] += pv;
                    totalPV += pv;
                }
            });

            const npv = totalPV - investment;
            const ratio = investment > 0 ? (totalPV / investment).toFixed(2) : "0";

            document.getElementById('result_ratio').innerText = ratio + " : 1";
            document.getElementById('result_pv').innerText = formatRupiah(totalPV);
            document.getElementById('result_npv').innerText = formatRupiah(npv);
            document.getElementById('result_investment').innerText = formatRupiah(investment);

            updateChart(years, yearlyPVs);
        }

        function updateChart(years, dataPV) {
            const ctx = document.getElementById('sroiChart').getContext('2d');
            const labels = Array.from({length: years}, (_, i) => `Tahun ${i + 1}`);
            if (sroiChart) sroiChart.destroy();
            sroiChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Present Value (Manfaat)',
                        data: dataPV,
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { callback: function(value) { return 'Rp ' + (value/1000000).toFixed(0) + ' Jt'; } } } }
                }
            });
        }

        // --- EXPORT TO EXCEL (XLSX) ---
        async function exportToExcel() {
            const workbook = new ExcelJS.Workbook();
            const sheet = workbook.addWorksheet('Laporan SROI');

            // 1. Header Laporan
            sheet.mergeCells('A1:G1');
            const titleCell = sheet.getCell('A1');
            titleCell.value = 'LAPORAN ANALISIS SROI - SYMBIOSIS APP';
            titleCell.font = { name: 'Arial', size: 16, bold: true, color: { argb: 'FFFFFFFF' } };
            titleCell.alignment = { horizontal: 'center', vertical: 'middle' };
            titleCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF16A34A' } }; // Hijau

            // Info Dasar
            sheet.mergeCells('A2:G2');
            sheet.getCell('A2').value = `Tanggal: ${new Date().toLocaleDateString('id-ID')} | User: {{ Auth::user()->full_name }}`;
            sheet.getCell('A2').alignment = { horizontal: 'center' };

            // 2. Parameter Investasi
            sheet.addRow([]);
            const paramTitle = sheet.addRow(['PARAMETER INVESTASI']);
            paramTitle.font = { bold: true };

            const inv = document.getElementById('total_investment').value;
            const rate = document.getElementById('discount_rate').value;
            const dur = document.getElementById('time_horizon').value;
            const ratio = document.getElementById('result_ratio').innerText;
            const npv = document.getElementById('result_npv').innerText;

            sheet.addRow(['Total Investasi', parseInt(inv), '', 'Rasio SROI', ratio]);
            sheet.addRow(['Discount Rate', rate + '%', '', 'NPV', npv]);
            sheet.addRow(['Durasi Proyek', dur + ' Tahun']);

            // Format Currency Cells
            sheet.getCell('B5').numFmt = '"Rp"#,##0';

            // 3. Tabel Dampak
            sheet.addRow([]);
            const tableHeader = sheet.addRow(['Nama Dampak', 'Nilai (Rp)', 'Deadweight', 'Attribution', 'Displacement', 'Drop-off']);

            // Styling Header Tabel
            ['A', 'B', 'C', 'D', 'E', 'F'].forEach(col => {
                const cell = sheet.getCell(`${col}${tableHeader.number}`);
                cell.font = { bold: true, color: { argb: 'FFFFFFFF' } };
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF4B5563' } }; // Abu Gelap
                cell.alignment = { horizontal: 'center' };
            });

            // Isi Data Tabel
            const rows = document.querySelectorAll('.outcome-row');
            rows.forEach(row => {
                const name = row.querySelector('.outcome-name').value;
                const val = parseInt(row.querySelector('.outcome-value').value);
                const dw = row.querySelector('.outcome-deadweight').value + '%';
                const att = row.querySelector('.outcome-attribution').value + '%';
                const disp = row.querySelector('.outcome-displacement').value + '%';
                const drop = row.querySelector('.outcome-dropoff').value + '%';

                const r = sheet.addRow([name, val, dw, att, disp, drop]);
                r.getCell(2).numFmt = '"Rp"#,##0'; // Format Rupiah kolom nilai
                // Center alignment for percentages
                [3,4,5,6].forEach(c => r.getCell(c).alignment = { horizontal: 'center' });
            });

            // Auto Width Columns
            sheet.columns = [
                { width: 30 }, { width: 20 }, { width: 15 }, { width: 15 }, { width: 15 }, { width: 15 }, { width: 10 }
            ];

            // 4. Menambahkan Grafik (Image)
            const canvas = document.getElementById('sroiChart');
            // Konversi canvas ke gambar base64
            const imageBase64 = canvas.toDataURL('image/png');

            const imageId = workbook.addImage({
                base64: imageBase64,
                extension: 'png',
            });

            // Tempel gambar di bawah tabel (beri jarak 2 baris)
            const startRow = sheet.lastRow.number + 2;
            sheet.getCell(`A${startRow}`).value = "GRAFIK PROYEKSI NILAI SOSIAL";
            sheet.getCell(`A${startRow}`).font = { bold: true };

            sheet.addImage(imageId, {
                tl: { col: 0, row: startRow }, // Mulai dari kolom A baris baru
                ext: { width: 600, height: 300 }
            });

            // 5. Save File
            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            saveAs(blob, 'Laporan_SROI_Symbiosis.xlsx');
        }

        document.addEventListener('DOMContentLoaded', calculateSROI);
    </script>
</x-app-layout>
