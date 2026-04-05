<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload);

        // 1. Validasi Keamanan (Wajib)
        $validSignatureKey = hash("sha512", $notification->order_id . $notification->status_code . $notification->gross_amount . config('midtrans.server_key'));

        if ($notification->signature_key != $validSignatureKey) {
            Log::warning('Midtrans Webhook Invalid Signature!', (array) $notification);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = $notification->transaction_status;
        $type = $notification->payment_type;
        $orderId = $notification->order_id; // Ini adalah transaction_number di DB kita

        // 2. Cari Transaksi di Database
        $transaction = Transaction::where('transaction_number', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // 3. Update Status Pembayaran berdasarkan Response Midtrans
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $transaction->update([
                'payment_status' => 'paid',
                // 'payment_type' => $type // Buka komen ini jika Anda menambahkan kolom payment_type di database
            ]);
        } elseif ($transactionStatus == 'pending') {
            $transaction->update([
                'payment_status' => 'unpaid'
            ]);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $transaction->update([
                'payment_status' => 'failed'
            ]);
        }

        // 4. Beri response 200 OK ke Midtrans agar mereka tidak melakukan spam pengiriman ulang
        return response()->json(['message' => 'Webhook Processed Successfully'], 200);
    }
}
