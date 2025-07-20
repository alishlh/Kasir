<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Str;

class ProductObserver
{
    public function creating(Product $product)
    {
        if (empty($product->image)) {
            $product->image = 'products/product-default.jpg';
        }

        // SKU Generation
        if (empty($product->sku)) {
           $product->sku = $this->generateSku($product);
        }

        // Barcode Generation
        if (empty($product->barcode)) {
            $product->barcode = $this->generateUniqueBarcode();
        }
    }

    private function generateSku(Product $product): string
    {
        // 1. Ambil 3 huruf pertama dari nama kategori
        // Pastikan relasi 'category' ada di model Product
        $category = $product->category;
        $categoryCode = 'GEN'; // Default code
        if ($category) {
            $categoryCode = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $category->name), 0, 3));
        }

        // 2. Ambil 3 huruf pertama dari setiap kata di nama produk
        $nameParts = explode(' ', $product->name);
        $nameCode = '';
        foreach ($nameParts as $part) {
            // Hanya ambil huruf, bersihkan dari angka atau simbol
            $cleanedPart = preg_replace('/[^a-zA-Z]/', '', $part);
            if (!empty($cleanedPart)) {
                $nameCode .= '-' . strtoupper(substr($cleanedPart, 0, 3));
            }
        }
        
        // Gabungkan kode kategori dan nama
        $baseSku = $categoryCode . $nameCode;

        // 3. Tambahkan nomor unik untuk menghindari duplikasi
        $latestProduct = Product::orderBy('id', 'desc')->first();
        $nextId = $latestProduct ? $latestProduct->id + 1 : 1;

        $uniqueSku = $baseSku . '-' . $nextId;

        // Cek lagi untuk memastikan SKU benar-benar unik
        $counter = 2;
        while (Product::where('sku', $uniqueSku)->exists()) {
            $uniqueSku = $baseSku . '-' . $nextId . '-' . $counter;
            $counter++;
        }

        return $uniqueSku;
    }

    private function generateUniqueBarcode(): string
    {
        do {
            // Menghasilkan 12 digit angka acak.
            // Kita gunakan awalan '200' yang umum untuk penggunaan internal toko.
            $base = '200' . str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
            
            // Menghitung Checksum Digit (digit ke-13)
            $sum = 0;
            for ($i = 0; $i < strlen($base); $i++) {
                // Kalikan dengan 3 untuk digit di posisi genap (index ganjil)
                $sum += (int)$base[$i] * (($i % 2 === 0) ? 1 : 3);
            }
            $checksum = (10 - ($sum % 10)) % 10;
            
            // Gabungkan menjadi 13 digit barcode
            $barcode = $base . $checksum;

        } while (Product::where('barcode', $barcode)->exists()); // Ulangi jika barcode sudah ada

        return $barcode;
    }
}
