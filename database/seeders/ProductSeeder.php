<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Asumsi Category ID:
        // 1 = Obat Bebas / Ringan
        // 2 = Obat Keras / Resep
        // 3 = Suplemen & Vitamin
        // 4 = Alat Kesehatan / Lainnya

        DB::table('products')->insert([
            // =================================================================
            // Kategori: Obat Bebas & Ringan (Analgesik, Flu, Maag)
            // =================================================================
            [
                'category_id' => 1,
                'name' => 'Sanmol Tablet 500mg (Strip)',
                'stock' => 200,
                'cost_price' => 2500,      // Modal
                'price' => 4000,           // Harga Eceran (Umum)
                'price_2' => 3000,         // Harga Grosir (Bidan)
                'price_racikan' => 3500,   // Harga untuk Racikan
                'sku' => 'OBT-SNM-TAB',
                'barcode' => '8991112223331',
                'description' => 'Paracetamol 500mg penurun panas dan pereda nyeri.',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'expiry_date' => '2027-12-31',
            ],
            [
                'category_id' => 1,
                'name' => 'Bodrex Tablet (Strip)',
                'stock' => 150,
                'cost_price' => 4500,
                'price' => 6000,
                'price_2' => 5000,
                'price_racikan' => 5500,
                'sku' => 'OBT-BDX-STD',
                'barcode' => '8992761132214',
                'description' => 'Meringankan sakit kepala, sakit gigi dan demam.',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'expiry_date' => '2026-05-01',
            ],
            [
                'category_id' => 1,
                'name' => 'Promag Tablet Kunyah (Sachet)',
                'stock' => 300,
                'cost_price' => 8000,
                'price' => 10000,
                'price_2' => 8500,
                'price_racikan' => 9500,
                'sku' => 'OBT-PMG-SCH',
                'barcode' => '8998866110055',
                'description' => 'Obat sakit maag dan kembung.',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'expiry_date' => '2027-01-20',
            ],
            [
                'category_id' => 1,
                'name' => 'Panadol Biru (Blister)',
                'stock' => 120,
                'cost_price' => 9500,
                'price' => 12500,
                'price_2' => 10500,
                'price_racikan' => 11500,
                'sku' => 'OBT-PND-BLU',
                'barcode' => '8993334445551',
                'description' => 'Paracetamol efektif meredakan nyeri dan demam.',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'expiry_date' => '2026-08-15',
            ],
            [
                'category_id' => 1,
                'name' => 'Procold Flu & Batuk',
                'stock' => 100,
                'cost_price' => 3500,
                'price' => 5000,
                'price_2' => 4000,
                'price_racikan' => 4500,
                'sku' => 'OBT-PCD-FLU',
                'barcode' => '8992221110009',
                'description' => 'Meredakan gejala flu, demam, sakit kepala dan batuk.',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'expiry_date' => '2026-11-10',
            ],

            // =================================================================
            // Kategori: Obat Keras & Antibiotik (Biasanya Resep)
            // =================================================================
            [
                'category_id' => 2,
                'name' => 'Amoxicillin 500mg (Strip)',
                'stock' => 500,
                'cost_price' => 5000,
                'price' => 8000,
                'price_2' => 6000,
                'price_racikan' => 7000, // Sering dipakai racikan
                'sku' => 'OBT-AMX-500',
                'barcode' => '8990001112223',
                'description' => 'Antibiotik untuk infeksi bakteri.',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'expiry_date' => '2027-06-30',
            ],
            [
                'category_id' => 2,
                'name' => 'Cefadroxil 500mg (Kapsul)',
                'stock' => 200,
                'cost_price' => 12000,
                'price' => 18000,
                'price_2' => 14000,
                'price_racikan' => 16000,
                'sku' => 'OBT-CFD-500',
                'barcode' => '8999998887776',
                'description' => 'Antibiotik spektrum luas.',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'expiry_date' => '2026-09-09',
            ],
            [
                'category_id' => 2,
                'name' => 'Amlodipine 5mg (Strip)',
                'stock' => 300,
                'cost_price' => 3000,
                'price' => 6000,
                'price_2' => 4000,
                'price_racikan' => 5000,
                'sku' => 'OBT-AML-005',
                'barcode' => '8997776665554',
                'description' => 'Obat hipertensi (darah tinggi).',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'expiry_date' => '2028-01-01',
            ],

            // =================================================================
            // Kategori: Sirup (Anak & Dewasa)
            // =================================================================
            [
                'category_id' => 1,
                'name' => 'OBH Combi Batuk Berdahak 100ml',
                'stock' => 80,
                'cost_price' => 14000,
                'price' => 18000,
                'price_2' => 15500,
                'price_racikan' => 18000, // Sirup jarang diracik campur, harga sama eceran
                'sku' => 'SYR-OBH-100',
                'barcode' => '8995554443332',
                'description' => 'Sirup obat batuk hitam rasa menthol.',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'expiry_date' => '2026-03-20',
            ],
            [
                'category_id' => 1,
                'name' => 'Termorex Sirup Turun Panas 60ml',
                'stock' => 60,
                'cost_price' => 11000,
                'price' => 15000,
                'price_2' => 12500,
                'price_racikan' => 15000,
                'sku' => 'SYR-TRM-060',
                'barcode' => '8991122334455',
                'description' => 'Sirup paracetamol bebas alkohol untuk anak.',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'expiry_date' => '2026-12-12',
            ],

            // =================================================================
            // Kategori: Vitamin & Suplemen
            // =================================================================
            [
                'category_id' => 3,
                'name' => 'Imboost Force Tablet (Strip)',
                'stock' => 50,
                'cost_price' => 40000,
                'price' => 55000,
                'price_2' => 45000,
                'price_racikan' => 50000,
                'sku' => 'VIT-IMB-FRC',
                'barcode' => '8998887776661',
                'description' => 'Suplemen daya tahan tubuh.',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'expiry_date' => '2026-05-30',
            ],
            [
                'category_id' => 3,
                'name' => 'Enervon-C Multivitamin (Botol 30)',
                'stock' => 40,
                'cost_price' => 35000,
                'price' => 45000,
                'price_2' => 38000,
                'price_racikan' => 45000,
                'sku' => 'VIT-ENV-030',
                'barcode' => '8996665554443',
                'description' => 'Vitamin C dan B Kompleks untuk menjaga stamina.',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'expiry_date' => '2027-02-14',
            ],

            // =================================================================
            // Kategori: Topikal (Salep/Luar)
            // =================================================================
            [
                'category_id' => 1,
                'name' => 'Kalpanax Krim 10g',
                'stock' => 75,
                'cost_price' => 12000,
                'price' => 16000,
                'price_2' => 13500,
                'price_racikan' => 16000,
                'sku' => 'TOP-KLP-010',
                'barcode' => '8994443332221',
                'description' => 'Krim antijamur untuk gatal kulit.',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'expiry_date' => '2027-08-08',
            ],
            [
                'category_id' => 1,
                'name' => 'Betadine Solution 15ml',
                'stock' => 90,
                'cost_price' => 15000,
                'price' => 20000,
                'price_2' => 17000,
                'price_racikan' => 20000,
                'sku' => 'TOP-BTD-015',
                'barcode' => '8991231231234',
                'description' => 'Antiseptik untuk luka luar.',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'expiry_date' => '2028-11-11',
            ],
            [
                'category_id' => 1,
                'name' => 'Minyak Kayu Putih Cap Lang 60ml',
                'stock' => 100,
                'cost_price' => 18000,
                'price' => 24000,
                'price_2' => 20000,
                'price_racikan' => 24000,
                'sku' => 'TOP-MKP-060',
                'barcode' => '8990987654321',
                'description' => 'Minyak kayu putih meredakan perut kembung.',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'expiry_date' => '2029-01-01',
            ],
        ]);
    }
}
