<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$database = "fresh_from_farm";


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_GET['query'])) {
    $search_query = $conn->real_escape_string($_GET['query']);

    
    $sql = "SELECT * FROM food_items WHERE name LIKE '%$search_query%'";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Search Results Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Search Results</h2>
            <?php
            if ($result && $result->num_rows > 0) {
                // Display search results
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="food-menu-box">';
                    echo '<div class="food-menu-img">';
                    echo '<img src="' . $row['image'] . '" alt="' . $row['name'] . '" class="img-responsive img-curve">';
                    echo '</div>';
                    echo '<div class="food-menu-desc">';
                    echo '<h4>' . $row['name'] . '</h4>';
                    echo '<p class="food-price">SLE ' . $row['price'] . '</p>';
                    echo '<p class="food-detail">Fresh from farm</p>';
                    echo '<br>';

                    // Add to Cart form with quantity input
                    echo '<form action="add_to_cart.php" method="POST">';
                    echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">'; // Assuming 'id' is the product ID
                    echo '<label for="quantity">Quantity:</label>';
                    echo '<input type="number" name="quantity" value="1" min="1" class="quantity-input">';
                    echo '<button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>';
                    echo '</form>';

                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No results found for your search.</p>';
            }

            
            $conn->close();
            ?>
        </div>
    </section>
    <!-- Search Results Section Ends Here -->
</body>
</html>