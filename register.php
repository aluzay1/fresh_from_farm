<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'fresh_from_farm');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $farm_type = $_POST['farm_type'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO farmers (name, email, phone, address, farm_type, username, password) 
            VALUES ('$name', '$email', '$phone', '$address', '$farm_type', '$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to confirmation page on success
        header('Location: registration_success.html?name=' . urlencode($name));
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
