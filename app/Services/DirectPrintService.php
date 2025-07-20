<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Setting;
use Mike42\Escpos\Printer;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Mike42\Escpos\EscposImage;
use Filament\Notifications\Notification;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
 

class DirectPrintService
{
    public function print($orderToPrint)
    {
        try {
            $order = Transaction::findOrFail($orderToPrint);
            $order_items = TransactionItem::where('order_id', $order->id)->get();
            $setting = Setting::first();

            // Sesuaikan nama printer Anda
            $connector = new WindowsPrintConnector($setting->name_printer_local);
            $printer = new Printer($connector);

            // Muat gambar logo
            $logo = EscposImage::load(public_path('storage/'. $setting->image), true);

            // Lebar kertas (58mm: 32 karakter, 80mm: 48 karakter)
            $lineWidth = 32;

            // Fungsi untuk merapikan teks
            function formatRow($name, $qty, $price, $lineWidth) {
                $nameWidth = 16; // Alokasi 16 karakter untuk nama produk
                $qtyWidth = 8;   // Alokasi 8 karakter untuk Qty
                $priceWidth = 8; // Alokasi 8 karakter untuk Harga

                // Bungkus nama produk jika panjangnya melebihi alokasi
                $nameLines = str_split($name, $nameWidth);

                // Siapkan variabel untuk hasil format
                $output = '';

                // Tambahkan semua baris nama produk kecuali yang terakhir
                for ($i = 0; $i < count($nameLines) - 1; $i++) {
                    $output .= str_pad($nameLines[$i], $lineWidth) . "\n"; // Baris dengan nama saja
                }

                // Baris terakhir dengan Qty dan Harga
                $lastLine = $nameLines[count($nameLines) - 1]; // Baris terakhir dari nama
                $lastLine = str_pad($lastLine, $nameWidth);   // Tambahkan padding untuk nama
                $qty = str_pad($qty, $qtyWidth, " ", STR_PAD_BOTH); // Qty di tengah
                $price = str_pad($price, $priceWidth, " ", STR_PAD_LEFT); // Harga di kanan

                // Gabungkan semua
                $output .= $lastLine . $qty . $price;

                return $output;
            }


            // Header Struk
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->bitImage($logo); // Cetak gambar logo
            $printer->setTextSize(1, 2);
            $printer->setEmphasis(true); // Tebal
            $printer->text($setting->shop . "\n");
            $printer->setTextSize(1, 1);
            $printer->setEmphasis(false); // Tebal
            $printer->text($setting->address . "\n");
            $printer->text($setting->phone ."\n");
            $printer->text("================================\n");

            // Detail Transaksi
            $printer->setJustification(Printer::JUSTIFY_LEFT); 
            $printer->text("No.Transaksi: " . $order->transaction_number . "\n");
            $printer->text("Pembayaran: " . $order->paymentMethod->name . "\n");
            $printer->text("Tanggal: " . $order->created_at->format('d-m-Y H:i:s') . "\n");
            $printer->text("================================\n");
            $printer->text(formatRow("Nama Barang", "Qty", "Harga", $lineWidth) . "\n");
            $printer->text("--------------------------------\n");
            foreach ($order_items as $item) {
                $product = Product::find( $item->product_id);
                $printer->text(formatRow($product->name ,$item->quantity , number_format($item->unit_price), $lineWidth) . "\n");
            }

            $printer->text("--------------------------------\n");

            $total = 0;
            foreach($order_items as $item) {
                $total += $item->quantity * $item->unit_price;
            }
            $printer->setEmphasis(true); // Tebal
            $printer->text(formatRow("Total","",number_format($total), $lineWidth) . "\n");
            $printer->text(formatRow("Nominal Bayar","",number_format($order->cash_received), $lineWidth) . "\n");
            $printer->text(formatRow("Kembalian","",number_format($order->change), $lineWidth) . "\n");
            $printer->setEmphasis(false); // Tebal

            // Footer Struk
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("================================\n");
            $printer->text("Terima Kasih!\n");
            $printer->text("================================\n");

            $printer->cut();
            $printer->close();
            Notification::make()
            ->title('Struk berhasil dicetak')
            ->success()
            ->send();
        } catch (\Exception $e) {
            Notification::make()
            ->title('Printer tidak terdaftar')
            ->icon('heroicon-o-printer')
            ->danger()
            ->send();
        }
    }
}
