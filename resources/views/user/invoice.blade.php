<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVOICE #{{ isset($transaction) ? $transaction->id : 'Preview' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 14px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .info {
            margin-bottom: 20px;
        }

        .info p {
            margin: 5px 0;
        }

        .info strong {
            display: inline-block;
            width: 100px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            border-top: 1px solid #000;
            padding-top: 10px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>E-KASIR USK</h1>
        <p>INVOICE #{{ isset($transaction) ? $transaction->id : 'Preview' }}</p>
        <p>Jl. Contoh No. 123, Banda Aceh | Telp: (0651) 123456</p>
    </div>

    <div class="info">
        <p><strong>Nama:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        @if(isset($transaction))
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaction->queue_date)->format('d F Y H:i') }}</p>
        <p><strong>Kasir:</strong> {{ Auth::user()->name }}</p>
        @if(isset($transaction->queue_number))
        <p><strong>No. Antrian:</strong> {{ $transaction->queue_number }}</p>
        @endif
        <p><strong>Status:</strong> {{ ucwords($transaction->status) }}</p>
        @if(isset($transaction->payment_method))
        <p><strong>Pembayaran:</strong>
            @switch($transaction->payment_method)
                @case('cash') TUNAI @break
                @case('card') KARTU @break
                @case('qris') QRIS @break
                @case('transfer') Transfer Bank @break
                @default {{ ucwords($transaction->payment_method) }}
            @endswitch
        </p>
        @endif
        @else
        <p><strong>Tanggal:</strong> {{ now()->format('d F Y H:i') }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Qty</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @foreach($items as $item)
            <tr>
                <td>{{ $counter++ }}</td>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td class="text-right">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total: Rp {{ number_format($total, 0, ',', '.') }}
    </div>

    <div class="footer">
        <p>Terima kasih atas kunjungan Anda</p>
        <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
        @if(isset($transaction))
        <p>Dibuat: {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
        @else
        <p>Preview: {{ now()->format('d/m/Y H:i') }}</p>
        @endif
    </div>
</body>
</html>
