<?php
session_start();
include('config.php');


if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    
    $stmt = $conn->prepare("SELECT * FROM orders1 WHERE order_id = ?");
    $stmt->bind_param("s", $order_id); 
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if an order was found
    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
    } else {
        $order = null; // Set to null if no order found
    }
} else {
    $order = null; // No order ID specified
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: #333;
        }

        h1 {
            margin-bottom: 20px;
            color: #007BFF;
            text-align: center;
        }

        
        .order-details {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 600px;
            margin-top: 20px;
            animation: fadeIn 0.5s ease-in-out; /* Animation for smooth appearance */
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .order-details h2 {
            margin-bottom: 15px;
            font-size: 24px;
            text-align: center;
            color: #007BFF;
        }

        .order-details p {
            margin: 10px 0;
            font-size: 16px;
            line-height: 1.6;
        }

        .order-details .total-amount {
            font-weight: bold;
            font-size: 18px;
            color: #28a745; 
            margin-top: 15px;
            text-align: center; 
        }

        /* Products Section */
        .order-details .products {
            margin-top: 15px;
            padding: 10px;
            background-color: #f1f1f1;
            border-left: 4px solid #007BFF; 
            border-radius: 5px; 
        }

        .order-details .products strong {
            color: #333;
        }

        /* Back Button */
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s, transform 0.3s; 
        }

        .btn-back:hover {
            background-color: #0056b3;
            transform: translateY(-2px); 
        }

       
        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 10px;
            text-align: center; 
        }
    </style>
</head>
<body>

    <h1>Thank You for Your Order!</h1>
    <div class="order-details">
        <h2>Order Confirmation</h2>
        
        <?php if ($order): ?>
            <p>Order ID: <strong><?php echo htmlspecialchars($order['order_id']); ?></strong></p>
            <p>Customer Name: <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong></p>
            <p>Email: <strong><?php echo htmlspecialchars($order['customer_email']); ?></strong></p>
            <p>Contact: <strong><?php echo htmlspecialchars($order['customer_contact']); ?></strong></p>
            <p>Address: <strong><?php echo htmlspecialchars($order['customer_address']); ?></strong></p>
            <p>Payment Method: <strong><?php echo htmlspecialchars($order['payment_method']); ?></strong></p>
            <p class="total-amount">Total Amount: <strong>SLE <?php echo number_format($order['total_amount'], 2); ?></strong></p>

            <h3>Products:</h3>
            <div class="products">
                <strong><?php echo htmlspecialchars($order['product_details']); ?></strong>
            </div>
        <?php else: ?>
            <div class="error-message">No order found with that ID.</div>
        <?php endif; ?>
        
        <a href="index.html" class="btn-back">Go Back to Shopping</a>
    </div>

</body>
</html>