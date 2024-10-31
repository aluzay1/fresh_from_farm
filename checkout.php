<?php
session_start();
include('config.php');

// Retrieve cart items and total amount from session
$cart_items = $_SESSION['cart_items'] ?? [];
$total_amount = $_SESSION['total_amount'] ?? 0;

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            max-width: 600px;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .checkout-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .checkout-form label {
            display: block;
            margin-bottom: 8px;
        }

        .checkout-form input[type="text"],
        .checkout-form input[type="email"],
        .checkout-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        .checkout-form input[type="text"]:focus,
        .checkout-form input[type="email"]:focus,
        .checkout-form select:focus {
            border-color: #007BFF;
            outline: none;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        .btn-success:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>';

echo "<h2>Checkout</h2>";

if (!empty($cart_items)) {
    echo "<table>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>";

    foreach ($cart_items as $item) {
        echo "<tr>
                <td>{$item['name']}</td>
                <td>SLE {$item['price']}</td>
                <td>{$item['quantity']}</td>
                <td>SLE {$item['subtotal']}</td>
              </tr>";
    }
    
    echo "</table>";
    echo "<h3>Total Amount: SLE {$total_amount}</h3>";
    
    // Checkout form
    echo '<form method="post" action="place_order.php" class="checkout-form">';
    echo '    <label for="customer_name">Name:</label>';
    echo '    <input type="text" id="customer_name" name="customer_name" required>';
    echo '    <label for="customer_email">Email:</label>';
    echo '    <input type="email" id="customer_email" name="customer_email" required>';
    echo '    <label for="customer_contact">Contact:</label>';
    echo '    <input type="text" id="customer_contact" name="customer_contact" required>';
    echo '    <label for="customer_address">Address:</label>';
    echo '    <input type="text" id="customer_address" name="customer_address" required>';
    echo '    <label for="payment_method">Payment Method:</label>';
    echo '    <select id="payment_method" name="payment_method" required>';
    echo '        <option value="Qmoney">Qmoney 031273043</option>';
    echo '        <option value="Card Payment">Card Payment</option>';
    echo '    </select>';
    echo '    <input type="hidden" name="total_amount" value="'.$total_amount.'">';
    echo '    <input type="submit" name="place_order" value="Place Order" class="btn-success">';
    echo '</form>';
} else {
    echo "<p>Your cart is empty. Cannot proceed to checkout.</p>";
}

echo '</body>
</html>';
?>
