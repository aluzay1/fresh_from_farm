<?php
session_start();
include('config.php');

// Generate a unique session ID if it doesn't exist
if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = session_id();
}

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $session_id = $_SESSION['session_id'];

    // Insert into the cart or update the quantity if the product already exists
    $stmt = $conn->prepare("INSERT INTO cart (session_id, product_id, quantity)
                            VALUES (?, ?, ?)
                            ON DUPLICATE KEY UPDATE quantity = quantity + ?");
    $stmt->bind_param("siii", $session_id, $product_id, $quantity, $quantity);
    $stmt->execute();

    header("Location: cart.php");
}
?>

