<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran #{{ $transaction->id }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #000;
        }

        .receipt-container {
            max-width: 300px;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 15px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }

        .header .address {
            font-size: 10px;
            margin: 2px 0;
        }

        .transaction-info {
            margin-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .info-row .label {
            font-weight: bold;
        }

        .items-section {
            margin: 10px 0;
        }

        .items-header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            align-items: flex-start;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .item-quantity-price {
            font-size: 10px;
            color: #666;
        }

        .item-total {
            text-align: right;
            font-weight: bold;
        }

        .total-section {
            border-top: 1px dashed #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: bold;
        }

        .payment-info {
            margin: 10px 0;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

        .footer {
            text-align: center;
            border-top: 2px solid #000;
            padding-top: 10px;
            margin-top: 10px;
        }

        .footer .thank-you {
            font-size: 11px;
            margin-bottom: 5px;
        }

        .footer .policy {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }

        .footer .timestamp {
            font-size: 10px;
            color: #666;
        }

        .cut-line {
            text-align: center;
            margin: 15px 0;
        }

        .cut-line .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .cut-line .text {
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <h1>E-KASIR USK</h1>
            <div class="address">Jl. Contoh No. 123, Banda Aceh</div>
            <div class="address">Telp: (0651) 123456</div>
        </div>

        <!-- Transaction Info -->
        <div class="transaction-info">
            <div class="info-row">
                <span class="label">Tanggal:</span>
                <span>{{ \Carbon\Carbon::parse($transaction->queue_date)->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Waktu:</span>
                <span>{{ $transaction->created_at->format('H:i:s') }}</span>
            </div>
            <div class="info-row">
                <span class="label">No. Transaksi:</span>
                <span>#{{ $transaction->id }}</span>
            </div>
            <div class="info-row">
                <span class="label">No. Antrian:</span>
                <span>{{ $transaction->queue_number }}</span>
            </div>
            <div class="info-row">
                <span class="label">Kasir:</span>
                <span>{{ Auth::user()->name }}</span>
            </div>
        </div>

        <!-- Items -->
        <div class="items-section">
            <div class="items-header">DAFTAR PEMBELIAN</div>

            @foreach(json_decode($transaction->items_json, true) as $item)
            <div class="item-row">
                <div class="item-details">
                    <div class="item-name">{{ $item['name'] }}</div>
                    <div class="item-quantity-price">{{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                </div>
                <div class="item-total">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
            </div>
            @endforeach

            <div class="total-section">
                <div class="total-row">
                    <span>TOTAL:</span>
                    <span>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="info-row">
                <span class="label">Metode Bayar:</span>
                <span>
                    @switch($transaction->payment_method)
                        @case('cash')
                            TUNAI
                            @break
                        @case('card')
                            KARTU
                            @break
                        @case('qris')
                            QRIS
                            @break
                        @case('transfer')
                            TRANSFER
                            @break
                        @default
                            {{ strtoupper($transaction->payment_method) }}
                    @endswitch
                </span>
            </div>
            <div class="info-row">
                <span class="label">Status:</span>
                <span>{{ strtoupper($transaction->status) }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="thank-you">Terima Kasih Atas Kunjungan Anda</div>
            <div class="policy">Barang yang sudah dibeli tidak dapat dikembalikan</div>
            <div class="timestamp">{{ now()->format('d/m/Y H:i:s') }}</div>
        </div>

        <!-- Cut Line -->
        <div class="cut-line">
            <div class="line"></div>
            <div class="text">================================</div>
        </div>
    </div>
</body>
</html>
