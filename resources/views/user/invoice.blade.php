<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-section {
            width: 48%;
        }
        .info-section h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .info-section p {
            margin: 5px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        .total-section p {
            margin: 5px 0;
            font-size: 14px;
        }
        .total-section .grand-total {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #333;
            padding-top: 10px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>E-Kasir Invoice</h1>
        <p>Point of Sale System</p>
        @if(isset($transaction))
        <p>Invoice #{{ $transaction->id }}</p>
        <p>Date: {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
        @else
        <p>Invoice Preview</p>
        <p>Date: {{ now()->format('d/m/Y H:i') }}</p>
        @endif
    </div>

    <div class="invoice-info">
        <div class="info-section">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            @if(isset($transaction))
            <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
            @endif
        </div>
        <div class="info-section">
            <h3>Store Information</h3>
            <p><strong>Store:</strong> E-Kasir</p>
            <p><strong>Address:</strong> Your Store Address</p>
            <p><strong>Phone:</strong> +62 xxx xxxx xxxx</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['name'] }}</td>
                <td class="text-right">{{ $item['quantity'] }}</td>
                <td class="text-right">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item['subtotal'] ?? ($item['price'] * $item['quantity']), 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <p><strong>Total:</strong> Rp {{ number_format($total, 0, ',', '.') }}</p>
        @if(isset($transaction))
        <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
        @endif
    </div>

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>This is a computer-generated invoice.</p>
    </div>
</body>
</html>
