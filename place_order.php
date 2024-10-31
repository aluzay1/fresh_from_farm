<?php
session_start();
include('config.php');

if (isset($_POST['place_order'])) {
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_contact = $_POST['customer_contact'];
    $customer_address = $_POST['customer_address'];
    $payment_method = $_POST['payment_method'];
    $total_amount = $_POST['total_amount'];
    $session_id = $_SESSION['session_id'];

    // Fetch cart items for the current session
    $stmt = $conn->prepare("SELECT c.quantity, f.name AS product_name 
                            FROM cart c 
                            JOIN food_items f ON c.product_id = f.id 
                            WHERE c.session_id = ?");
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    $cart_result = $stmt->get_result();

    // Initialize arrays to hold product names and quantities
    $product_names = [];
    $quantities = [];

    // Loop through cart items and build the product details array
    while ($row = $cart_result->fetch_assoc()) {
        $product_names[] = $row['product_name'];
        $quantities[] = $row['quantity'];
    }

    // Convert the product names and quantities arrays to strings
    $product_names_string = implode(", ", $product_names);
    $quantities_string = implode(", ", $quantities);
    
    // Insert the order into orders1 table
    $stmt_order = $conn->prepare("INSERT INTO orders1 
                            (customer_name, customer_email, customer_contact, customer_address, 
                             payment_method, total_amount, product_name, quantity, product_details) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $product_details_string = implode(", ", array_map(function($name, $quantity) {
        return "$name (Qty: $quantity)";
    }, $product_names, $quantities));

    $stmt_order->bind_param("sssssdsss", $customer_name, $customer_email, $customer_contact, $customer_address, 
                            $payment_method, $total_amount, $product_names_string, $quantities_string, $product_details_string);
    $stmt_order->execute();

    // Optionally, clear the cart here if desired
    // $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ?");
    // $stmt->bind_param("s", $session_id);
    // $stmt->execute();

    // Redirect to order confirmation
    header("Location: order_confirmation.php?order_id=" . $conn->insert_id); // Pass the order_id
    exit();
}
?>
