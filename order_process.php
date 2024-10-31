<?php
session_start();
include('config.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get customer information and total amount
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_contact = $_POST['customer_contact'];
    $customer_address = $_POST['customer_address'];
    $payment_method = $_POST['payment_method'];
    $total_amount = $_POST['total_amount'];

    // Prepare the SQL statement to insert the order
    $sql = "INSERT INTO orders1 (customer_name, customer_email, customer_contact, customer_address, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssd", $customer_name, $customer_email, $customer_contact, $customer_address, $payment_method, $total_amount);

    if ($stmt->execute()) {
        // Clear the cart session after successful order
        unset($_SESSION['cart']);
        echo "Order placed successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
