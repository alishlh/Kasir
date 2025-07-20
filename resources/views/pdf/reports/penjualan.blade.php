<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Laporan Order</title>
    <style>
        body {
            margin: 0 auto;
            font-family: Arial, sans-serif;
            background: #FFFFFF;
            font-size: 12px;
            color: #001028;
        }

        header {
            padding: 10px 0;
            text-align: center;
            border-bottom: 1px solid #5D6975;
            margin-bottom: 20px;
        }

        #logo img {
            width: 120px;
        }

         h1 {
            font-size: 2em;
            margin: 14px 0;
        }

        span {
            font-size: 14px;
            color: #5D6975;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #C1CED9;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #F5F5F5;
            color: #5D6975;
        }

        .desc {
            text-align: left;
        }

        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 30px;
            border-top: 1px solid #C1CED9;
            text-align: center;
            padding: 8px 0;
            font-size: 0.8em;
            color: #5D6975;
        }
    </style>
</head>

<body>

    <header>
        <div id="logo">
            <img src="{{ storage_path('app/public/' . $logo) }}" alt="{{ asset('storage/' . $logo) }}">
        </div>
        <h1>Laporan Penjualan<br><span>{{ '(' . $fileName . ')' }}</span></h1>
    </header>

    <main>
    <?php $total_Order_amount = 0?>
    <?php $total_Profit_amount = 0?>
        @foreach($data as $order)
        <table>
            <thead>
                <tr>
                    <th colspan="4" style="background-color:yellow; color:black;">No.Transaksi: {{ $order->transaction_number }}</th>
                    <th colspan="2" style="background-color:yellow; color:black;">Pembayaran: {{ $order->paymentMethod->name }}</th>
                </tr>
                <tr>
                    <th>Produk</th>
                    <th>Harga Modal</th>
                    <th>Harga Jual</th>
                    <th>Qty</th>
                    <th>Total Bayar</th>
                    <th>Total Profit</th>
                </tr>
            </thead>
            <tbody>
                <?php $total_profit_amount = 0 ?>
                @foreach($order->transactionItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>Rp {{ number_format($item->cost_price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->total_profit, 0, ',', '.') }}</td>
                    </tr>
                    <?php $total_profit_amount += $item->total_profit ?>
                @endforeach
                    <tr>
                        <td colspan="4">Total</td>
                        <td>Rp {{ number_format( $order->total, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format( $total_profit_amount, 0, ',', '.') }}</td>
                    </tr>
            </tbody>
        </table>
        <?php $total_Order_amount += $order->total ?>
        <?php $total_Profit_amount += $total_profit_amount ?>
        @endforeach

        <table>
            <thead>
                <tr>
                    <th style="background-color:white; color:black; font-size:16px">Total Uang Masuk: Rp {{ number_format( $total_Order_amount, 0, ',', '.') }}</th>
                    <th style="background-color:white; color:black; font-size:16px">Total Keuntungan: Rp {{ number_format( $total_Profit_amount, 0, ',', '.') }}</th>
                </tr>
            </thead>
        </table>
    </main>

    <footer>
        Laporan ini dihasilkan secara otomatis tanpa tanda tangan.
    </footer>

</body>

</html>
