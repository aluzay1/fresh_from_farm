<?php
session_start();
include('config.php');

$session_id = $_SESSION['session_id'];
$total_amount = 0;

// Handle 'Clear Cart' button logic
if (isset($_POST['clear_cart'])) {
    $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ?");
    $stmt->bind_param("s", $session_id);
    if ($stmt->execute()) {
        echo "<script>alert('Cart cleared successfully!'); window.location.href='cart.php';</script>";
    } else {
        echo "Error clearing cart: " . $conn->error;
    }
}

// Fetch cart items
$stmt = $conn->prepare("SELECT c.quantity, f.name, f.price 
                        FROM cart c 
                        JOIN food_items f ON c.product_id = f.id 
                        WHERE c.session_id = ?");
$stmt->bind_param("s", $session_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
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

        .cart-page {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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

        .btn {
            display: inline-block;
            padding: 10px 15px;
            color: white;
            background-color: #007BFF;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #08e08d;
        }

        .btn-primary:hover {
            background-color: #7ef58e;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        p {
            color: #555;
        }
    </style>
</head>
<body>
    <div class="cart-page">
        <h2>Your Cart</h2>

        <?php
        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>";

            // Store cart items in session
            $cart_items = [];

            while ($row = $result->fetch_assoc()) {
                $subtotal = $row['price'] * $row['quantity'];
                $total_amount += $subtotal;

                $cart_items[] = [
                    'name' => $row['name'],
                    'price' => $row['price'],
                    'quantity' => $row['quantity'],
                    'subtotal' => $subtotal
                ];

                echo "<tr>
                        <td>{$row['name']}</td>
                        <td>SLE {$row['price']}</td>
                        <td>{$row['quantity']}</td>
                        <td>SLE {$subtotal}</td>
                      </tr>";
            }
            echo "</table>";
            echo "<h3>Total Amount: SLE {$total_amount}</h3>";

            // Store cart items and total in session
            $_SESSION['cart_items'] = $cart_items;
            $_SESSION['total_amount'] = $total_amount;

            echo '<a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>';
        } else {
            echo "<p>Your cart is empty.</p>";
        }
        ?>

        <!-- Clear Cart Button -->
        <form method="POST" action="" style="margin-top: 10px;">
            <button type="submit" name="clear_cart" class="btn btn-danger">Clear Cart</button>
        </form>
    </div>
</body>
</html>