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

        DB::table('products')->insert([
            // =================================================================
            // Kategori 1: Makanan Ringan
            // =================================================================
            [
                'category_id' => 1, 'name' => 'Qtela Singkong Original 60g', 'stock' => 150, 'cost_price' => 4500, 'price' => 5500, 'sku' => 'QT-SK-ORI-60', 'barcode' => '8992761132214', 'description' => 'Keripik singkong renyah rasa original.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 1, 'name' => 'Indomie Goreng Original', 'stock' => 200, 'cost_price' => 2800, 'price' => 3500, 'sku' => 'IND-GR-ORI-85', 'barcode' => '089686040011', 'description' => 'Mi instan goreng rasa original legendaris.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 1, 'name' => 'Chitato Sapi Panggang 68g', 'stock' => 120, 'cost_price' => 8500, 'price' => 10000, 'sku' => 'CHT-SP-68', 'barcode' => '8998866110037', 'description' => 'Keripik kentang dengan bumbu sapi panggang.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 1, 'name' => 'Lays Rumput Laut 68g', 'stock' => 90, 'cost_price' => 8500, 'price' => 10000, 'sku' => 'LYS-RL-68', 'barcode' => '8992761158016', 'description' => 'Keripik kentang tipis rasa rumput laut.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 1, 'name' => 'Silverqueen Cashew 58g', 'stock' => 80, 'cost_price' => 10000, 'price' => 12500, 'sku' => 'SVQ-CS-58', 'barcode' => '8991001101119', 'description' => 'Cokelat susu dengan kacang mede.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 1, 'name' => 'Beng-Beng Regular 20g', 'stock' => 300, 'cost_price' => 1500, 'price' => 2000, 'sku' => 'BBG-REG-20', 'barcode' => '8996001300018', 'description' => 'Wafer karamel renyah berlapis cokelat.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 1, 'name' => 'Tango Wafer Cokelat 130g', 'stock' => 70, 'cost_price' => 6000, 'price' => 7500, 'sku' => 'TGO-CK-130', 'barcode' => '8991001201017', 'description' => 'Wafer renyah dengan krim cokelat tebal.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 1, 'name' => 'Pringles Original 107g', 'stock' => 50, 'cost_price' => 20000, 'price' => 24000, 'sku' => 'PRG-ORI-107', 'barcode' => '038000138435', 'description' => 'Keripik kentang dalam tabung rasa original.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 1, 'name' => 'Oishi Pillows Cokelat 100g', 'stock' => 100, 'cost_price' => 7000, 'price' => 8500, 'sku' => 'OIS-PLC-100', 'barcode' => '8995166102148', 'description' => 'Biskuit renyah dengan isian krim cokelat.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 1, 'name' => 'Nabati Wafer Keju 50g', 'stock' => 180, 'cost_price' => 1500, 'price' => 2000, 'sku' => 'NBT-KJ-50', 'barcode' => '8993175535017', 'description' => 'Wafer renyah dengan krim keju richeese.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],

            // =================================================================
            // Kategori 2: Minuman
            // =================================================================
            [
                'category_id' => 2, 'name' => 'Aqua Air Mineral 600ml', 'stock' => 300, 'cost_price' => 2500, 'price' => 3500, 'sku' => 'AQA-MIN-600', 'barcode' => '8992761141124', 'description' => 'Air mineral murni dalam kemasan botol 600ml.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 2, 'name' => 'Teh Kotak Sosro 200ml', 'stock' => 250, 'cost_price' => 3000, 'price' => 4000, 'sku' => 'SOS-TK-200', 'barcode' => '8998899000108', 'description' => 'Minuman teh melati siap saji.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 2, 'name' => 'Coca-Cola Kaleng 330ml', 'stock' => 150, 'cost_price' => 4500, 'price' => 6000, 'sku' => 'CC-KLG-330', 'barcode' => '8992761101111', 'description' => 'Minuman soda rasa kola dalam kemasan kaleng.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 2, 'name' => 'Fanta Strawberry Botol 390ml', 'stock' => 130, 'cost_price' => 4000, 'price' => 5500, 'sku' => 'FNT-ST-390', 'barcode' => '8992761102224', 'description' => 'Minuman soda rasa stroberi.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 2, 'name' => 'Sprite Botol 390ml', 'stock' => 140, 'cost_price' => 4000, 'price' => 5500, 'sku' => 'SPR-LN-390', 'barcode' => '8992761103337', 'description' => 'Minuman soda rasa lemon-lime.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 2, 'name' => 'Ultra Milk Cokelat 250ml', 'stock' => 180, 'cost_price' => 5000, 'price' => 6500, 'sku' => 'ULT-CK-250', 'barcode' => '8998899102017', 'description' => 'Susu UHT rasa cokelat.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 2, 'name' => 'Pocari Sweat Botol 500ml', 'stock' => 100, 'cost_price' => 6500, 'price' => 8000, 'sku' => 'PCS-500', 'barcode' => '4987035332517', 'description' => 'Minuman isotonik pengganti cairan tubuh.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 2, 'name' => 'Floridina Orange 350ml', 'stock' => 160, 'cost_price' => 2500, 'price' => 3500, 'sku' => 'FLD-ORG-350', 'barcode' => '8998866601019', 'description' => 'Minuman sari buah jeruk dengan bulir asli.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 2, 'name' => 'Frestea Teh Melati 500ml', 'stock' => 170, 'cost_price' => 4000, 'price' => 5000, 'sku' => 'FRS-TM-500', 'barcode' => '8992761111127', 'description' => 'Minuman teh rasa melati.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 2, 'name' => 'Le Minerale 600ml', 'stock' => 280, 'cost_price' => 2500, 'price' => 3500, 'sku' => 'LMN-600', 'barcode' => '8992761191129', 'description' => 'Air mineral dengan kandungan mineral alami.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],

            // =================================================================
            // Kategori 3: Alat Tulis Kantor (ATK)
            // =================================================================
            [
                'category_id' => 3, 'name' => 'Buku Tulis Sinar Dunia 38 Lbr', 'stock' => 0, 'cost_price' => 2000, 'price' => 3000, 'sku' => 'SIDU-BK-38', 'barcode' => '8991389000017', 'description' => 'Buku tulis ukuran standar isi 38 lembar.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 3, 'name' => 'Pulpen Standard AE7 Hitam', 'stock' => 0, 'cost_price' => 1500, 'price' => 2500, 'sku' => 'STD-AE7-BLK', 'barcode' => '8991831101019', 'description' => 'Pulpen tinta hitam dengan mata pena 0.5mm.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 3, 'name' => 'Pensil 2B Faber-Castell', 'stock' => 0, 'cost_price' => 2500, 'price' => 4000, 'sku' => 'FC-P2B', 'barcode' => '8991338000021', 'description' => 'Pensil berkualitas untuk ujian dan menulis.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 3, 'name' => 'Penghapus Faber-Castell', 'stock' => 0, 'cost_price' => 2000, 'price' => 3500, 'sku' => 'FC-ERASER', 'barcode' => '4005401871715', 'description' => 'Penghapus bebas debu dan bersih.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 3, 'name' => 'Penggaris Butterfly 30cm', 'stock' => 0, 'cost_price' => 1500, 'price' => 2500, 'sku' => 'BFLY-RUL-30', 'barcode' => '8991320001150', 'description' => 'Penggaris plastik transparan 30 cm.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 3, 'name' => 'Spidol Snowman Boardmarker Hitam', 'stock' => 0, 'cost_price' => 6000, 'price' => 8000, 'sku' => 'SNOW-BM-BLK', 'barcode' => '8993888120015', 'description' => 'Spidol untuk papan tulis putih, mudah dihapus.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 3, 'name' => 'Kertas HVS A4 Sinar Dunia 75gsm', 'stock' => 0, 'cost_price' => 40000, 'price' => 48000, 'sku' => 'SIDU-A4-75', 'barcode' => '8991389100113', 'description' => 'Satu rim kertas HVS ukuran A4 75 gsm.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 3, 'name' => 'Tipe-X Kenko KE-01', 'stock' => 0, 'cost_price' => 4000, 'price' => 6000, 'sku' => 'KNK-KE01', 'barcode' => '8993993000109', 'description' => 'Cairan koreksi tulisan cepat kering.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 3, 'name' => 'Lem Kertas Glukol 22ml', 'stock' => 0, 'cost_price' => 1000, 'price' => 2000, 'sku' => 'GLU-22ML', 'barcode' => '8991320000016', 'description' => 'Lem kertas cair serbaguna.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 3, 'name' => 'Stabilo Boss Original Kuning', 'stock' => 0, 'cost_price' => 7000, 'price' => 9000, 'sku' => 'STB-BS-YLW', 'barcode' => '4006381155018', 'description' => 'Text highlighter warna kuning neon.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],

            // =================================================================
            // Kategori 4: Produk Kebersihan
            // =================================================================
            [
                'category_id' => 4, 'name' => 'Sabun Lifebuoy Total 10 85g', 'stock' => 0, 'cost_price' => 3500, 'price' => 4500, 'sku' => 'LFB-T10-85', 'barcode' => '8999999039014', 'description' => 'Sabun mandi batang anti-bakteri.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 4, 'name' => 'Sampo Pantene Anti Lepek 135ml', 'stock' => 0, 'cost_price' => 18000, 'price' => 22000, 'sku' => 'PNT-AL-135', 'barcode' => '4902430727017', 'description' => 'Sampo untuk rambut bebas lepek.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 4, 'name' => 'Sikat Gigi Pepsodent Double Care', 'stock' => 0, 'cost_price' => 5000, 'price' => 7500, 'sku' => 'PSD-SG-DC', 'barcode' => '8999999038017', 'description' => 'Sikat gigi dengan bulu halus dan lembut.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 4, 'name' => 'Pasta Gigi Pepsodent 190g', 'stock' => 0, 'cost_price' => 10000, 'price' => 13000, 'sku' => 'PSD-PG-190', 'barcode' => '8999999001011', 'description' => 'Pasta gigi pencegah gigi berlubang.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 4, 'name' => 'Sunlight Pencuci Piring Jeruk Nipis 755ml', 'stock' => 0, 'cost_price' => 12000, 'price' => 15000, 'sku' => 'SN-JN-755', 'barcode' => '8999999039618', 'description' => 'Sabun cuci piring dengan ekstrak jeruk nipis.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 4, 'name' => 'Wipol Karbol Wangi Classic Pine 780ml', 'stock' => 0, 'cost_price' => 15000, 'price' => 18000, 'sku' => 'WPL-CP-780', 'barcode' => '8999999042014', 'description' => 'Cairan disinfektan pembersih lantai.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 4, 'name' => 'Super Pell Pembersih Lantai 770ml', 'stock' => 0, 'cost_price' => 10000, 'price' => 13000, 'sku' => 'SPP-PL-770', 'barcode' => '8999999039410', 'description' => 'Pembersih lantai dengan teknologi power clean.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 4, 'name' => 'Baygon Semprot Flower Garden 600ml', 'stock' => 0, 'cost_price' => 30000, 'price' => 35000, 'sku' => 'BYG-FG-600', 'barcode' => '8991999000115', 'description' => 'Insektisida semprot pembasmi nyamuk & serangga.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 4, 'name' => 'Rinso Deterjen Anti Noda 770g', 'stock' => 0, 'cost_price' => 18000, 'price' => 21000, 'sku' => 'RNS-AN-770', 'barcode' => '8999999540019', 'description' => 'Deterjen bubuk penghilang noda membandel.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'category_id' => 4, 'name' => 'Molto Pewangi Pakaian Pink 820ml', 'stock' => 0, 'cost_price' => 14000, 'price' => 17000, 'sku' => 'MLT-PK-820', 'barcode' => '8999999041017', 'description' => 'Pewangi dan pelembut pakaian konsentrat.', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ],
        ]);
    }
}