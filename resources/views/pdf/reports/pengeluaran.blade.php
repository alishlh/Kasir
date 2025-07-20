<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Laporan Uang Keluar</title>
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
            width: 80px;
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
        <h1>Laporan Uang Keluar<br><span>{{ '(' . $fileName . ')' }}</span></h1>
    </header>

    <main>
    <?php $total_Order_amount = 0?>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Tipe</th>
                    <th>Sumber</th>
                    <th>Total</th>
                    <th>notes</th>
                </tr>
            </thead>
            <tbody>
        @foreach($data as $d)
                    <tr>
                        <td>{{ $d->updated_at->format('d-m-Y') }}</td>
                        <td>{{ app(App\Services\CashFlowLabelService::class)->getTypeLabel($d->type) }}</td>
                        <td>{{ app(App\Services\CashFlowLabelService::class)->getSourceLabel($d->type, $d->source) }}</td>
                        <td>Rp {{ number_format($d->amount, 0, ',', '.') }}</td>
                        <td style="width: 200px;">{{ $d->notes }}</td>
                    </tr>
        <?php $total_Order_amount += $d->amount ?>
        @endforeach
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th colspan="5" style="background-color:white; color:black; font-size:16px">Total Keseluruhan: Rp {{ number_format( $total_Order_amount, 0, ',', '.') }}</th>
                </tr>
            </thead>
        </table>
    </main>

    <footer>
        Laporan ini dihasilkan secara otomatis tanpa tanda tangan.
    </footer>

</body>

</html>
