<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $stores = Store::all();

        $products = [
            // Kategori: Obat Bebas & Ringan
            ['category_name' => 'Obat Bebas (OTC) & Ringan', 'name' => 'Sanmol Tablet 500mg (Strip)', 'stock' => 200, 'cost_price' => 2500, 'price' => 4000, 'price_2' => 3000, 'price_racikan' => 3500, 'sku' => 'OBT-SNM-TAB', 'barcode' => '8991112223331', 'description' => 'Paracetamol 500mg penurun panas dan pereda nyeri.', 'expiry_date' => '2027-12-31'],
            ['category_name' => 'Obat Bebas (OTC) & Ringan', 'name' => 'Bodrex Tablet (Strip)', 'stock' => 150, 'cost_price' => 4500, 'price' => 6000, 'price_2' => 5000, 'price_racikan' => 5500, 'sku' => 'OBT-BDX-STD', 'barcode' => '8992761132214', 'description' => 'Meringankan sakit kepala, sakit gigi dan demam.', 'expiry_date' => '2026-05-01'],
            ['category_name' => 'Obat Bebas (OTC) & Ringan', 'name' => 'Promag Tablet Kunyah (Sachet)', 'stock' => 300, 'cost_price' => 8000, 'price' => 10000, 'price_2' => 8500, 'price_racikan' => 9500, 'sku' => 'OBT-PMG-SCH', 'barcode' => '8998866110055', 'description' => 'Obat sakit maag dan kembung.', 'expiry_date' => '2027-01-20'],
            ['category_name' => 'Obat Bebas (OTC) & Ringan', 'name' => 'Panadol Biru (Blister)', 'stock' => 120, 'cost_price' => 9500, 'price' => 12500, 'price_2' => 10500, 'price_racikan' => 11500, 'sku' => 'OBT-PND-BLU', 'barcode' => '8993334445551', 'description' => 'Paracetamol efektif meredakan nyeri dan demam.', 'expiry_date' => '2026-08-15'],
            ['category_name' => 'Obat Bebas (OTC) & Ringan', 'name' => 'Procold Flu & Batuk', 'stock' => 100, 'cost_price' => 3500, 'price' => 5000, 'price_2' => 4000, 'price_racikan' => 4500, 'sku' => 'OBT-PCD-FLU', 'barcode' => '8992221110009', 'description' => 'Meredakan gejala flu, demam, sakit kepala dan batuk.', 'expiry_date' => '2026-11-10'],

            // Kategori: Obat Keras & Antibiotik
            ['category_name' => 'Obat Keras & Resep', 'name' => 'Amoxicillin 500mg (Strip)', 'stock' => 500, 'cost_price' => 5000, 'price' => 8000, 'price_2' => 6000, 'price_racikan' => 7000, 'sku' => 'OBT-AMX-500', 'barcode' => '8990001112223', 'description' => 'Antibiotik untuk infeksi bakteri.', 'expiry_date' => '2027-06-30'],
            ['category_name' => 'Obat Keras & Resep', 'name' => 'Cefadroxil 500mg (Kapsul)', 'stock' => 200, 'cost_price' => 12000, 'price' => 18000, 'price_2' => 14000, 'price_racikan' => 16000, 'sku' => 'OBT-CFD-500', 'barcode' => '8999998887776', 'description' => 'Antibiotik spektrum luas.', 'expiry_date' => '2026-09-09'],
            ['category_name' => 'Obat Keras & Resep', 'name' => 'Amlodipine 5mg (Strip)', 'stock' => 300, 'cost_price' => 3000, 'price' => 6000, 'price_2' => 4000, 'price_racikan' => 5000, 'sku' => 'OBT-AML-005', 'barcode' => '8997776665554', 'description' => 'Obat hipertensi (darah tinggi).', 'expiry_date' => '2028-01-01'],

            // Kategori: Sirup
            ['category_name' => 'Obat Bebas (OTC) & Ringan', 'name' => 'OBH Combi Batuk Berdahak 100ml', 'stock' => 80, 'cost_price' => 14000, 'price' => 18000, 'price_2' => 15500, 'price_racikan' => 18000, 'sku' => 'SYR-OBH-100', 'barcode' => '8995554443332', 'description' => 'Sirup obat batuk hitam rasa menthol.', 'expiry_date' => '2026-03-20'],
            ['category_name' => 'Obat Bebas (OTC) & Ringan', 'name' => 'Termorex Sirup Turun Panas 60ml', 'stock' => 60, 'cost_price' => 11000, 'price' => 15000, 'price_2' => 12500, 'price_racikan' => 15000, 'sku' => 'SYR-TRM-060', 'barcode' => '8991122334455', 'description' => 'Sirup paracetamol bebas alkohol untuk anak.', 'expiry_date' => '2026-12-12'],

            // Kategori: Vitamin & Suplemen
            ['category_name' => 'Vitamin & Suplemen', 'name' => 'Imboost Force Tablet (Strip)', 'stock' => 50, 'cost_price' => 40000, 'price' => 55000, 'price_2' => 45000, 'price_racikan' => 50000, 'sku' => 'VIT-IMB-FRC', 'barcode' => '8998887776661', 'description' => 'Suplemen daya tahan tubuh.', 'expiry_date' => '2026-05-30'],
            ['category_name' => 'Vitamin & Suplemen', 'name' => 'Enervon-C Multivitamin (Botol 30)', 'stock' => 40, 'cost_price' => 35000, 'price' => 45000, 'price_2' => 38000, 'price_racikan' => 45000, 'sku' => 'VIT-ENV-030', 'barcode' => '8996665554443', 'description' => 'Vitamin C dan B Kompleks untuk menjaga stamina.', 'expiry_date' => '2027-02-14'],

            // Kategori: Topikal
            ['category_name' => 'Obat Bebas (OTC) & Ringan', 'name' => 'Kalpanax Krim 10g', 'stock' => 75, 'cost_price' => 12000, 'price' => 16000, 'price_2' => 13500, 'price_racikan' => 16000, 'sku' => 'TOP-KLP-010', 'barcode' => '8994443332221', 'description' => 'Krim antijamur untuk gatal kulit.', 'expiry_date' => '2027-08-08'],
            ['category_name' => 'Obat Bebas (OTC) & Ringan', 'name' => 'Betadine Solution 15ml', 'stock' => 90, 'cost_price' => 15000, 'price' => 20000, 'price_2' => 17000, 'price_racikan' => 20000, 'sku' => 'TOP-BTD-015', 'barcode' => '8991231231234', 'description' => 'Antiseptik untuk luka luar.', 'expiry_date' => '2028-11-11'],
            ['category_name' => 'Obat Bebas (OTC) & Ringan', 'name' => 'Minyak Kayu Putih Cap Lang 60ml', 'stock' => 100, 'cost_price' => 18000, 'price' => 24000, 'price_2' => 20000, 'price_racikan' => 24000, 'sku' => 'TOP-MKP-060', 'barcode' => '8990987654321', 'description' => 'Minyak kayu putih meredakan perut kembung.', 'expiry_date' => '2029-01-01'],
        ];

        foreach ($stores as $store) {
            foreach ($products as $product) {
                // Find the category for this store
                $category = Category::where('store_id', $store->id)
                    ->where('name', $product['category_name'])
                    ->first();

                DB::table('products')->insert([
                    'store_id' => $store->id,
                    'category_id' => $category?->id,
                    'name' => $product['name'],
                    'stock' => $product['stock'],
                    'cost_price' => $product['cost_price'],
                    'price' => $product['price'],
                    'price_2' => $product['price_2'],
                    'price_racikan' => $product['price_racikan'],
                    'sku' => $product['sku'],
                    'barcode' => $product['barcode'],
                    'description' => $product['description'],
                    'is_active' => 1,
                    'expiry_date' => $product['expiry_date'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
