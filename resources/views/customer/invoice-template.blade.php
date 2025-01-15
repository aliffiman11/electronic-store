<!DOCTYPE html>
<html>
<head>
    <title>Invoice - {{ $orderReference }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: gray;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: gray;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Invoice</h1>
        <p>Order Reference: {{ $orderReference }}</p>
        <p>Date: {{ $createdDate }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price (RM)</th>
                <th>Total (RM)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->product->name ?? 'Product Not Found' }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>{{ number_format($order->price, 2) }}</td>
                    <td>{{ number_format($order->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total Items:</strong> {{ $totalItems }}</p>
    <p><strong>Total Price:</strong> RM {{ number_format($totalPrice, 2) }}</p>

    <div class="footer">
        <p>Thank you for your purchase!</p>
    </div>
</body>
</html>
