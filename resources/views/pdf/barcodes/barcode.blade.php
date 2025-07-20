<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            text-align: center;
        }
        table {
            width: 100%;
        }
        td {
            border: 1px solid #000;
            padding: 10px;
            width: 20%; /* 5 kolom, berarti 100% / 5 = 20% */
            text-align: center;
        }
        img {
            width: 120px;
            height: 30px;
        }
        p {
            font-size: 10px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    @foreach (collect($barcodes)->groupBy('number') as $number => $items)
        @php
            $barcode = $items->first(); // Ambil data 1 barcode untuk info (name, price, barcode image)
        @endphp

        <h2>Barcode: {{ $barcode['name'] }} - Rp. {{ number_format($barcode['price'], 0, ',', '.') }}</h2>

        <table>
            @for ($i = 0; $i < 45; $i++)
                @if ($i % 5 == 0)
                    <tr>
                @endif

                <td style="padding: 10px; text-align: center;">
                    <p>{{ $barcode['name'] }}<br>Rp. {{ number_format($barcode['price'], 0, ',', '.') }}</p>
                    <img src="{{ $barcode['barcode'] }}" alt="{{ $barcode['number'] }}"><br>
                    {{ $barcode['number'] }}
                </td>

                @if (($i + 1) % 5 == 0)
                    </tr>
                @endif
            @endfor
        </table>

        <div style="page-break-after: always;"></div>
    @endforeach
</body>

</html>
