<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
</head>
<body>

<h2>Search Results</h2>

<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Database connection settings
    $host = "localhost";
    $username = "root";
    $user_pass = "root";
    $database_in_use = "Comp_440_Project";

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize user input
    $category = $_GET["category"];
    $safeCategory = $conn->real_escape_string($category);

    // Search items by category
    $searchItemsSQL = "SELECT * FROM Items 
                       INNER JOIN ItemCategories ON Items.ItemID = ItemCategories.ItemID
                       WHERE ItemCategories.CategoryName = '$safeCategory'";
    $result = $conn->query($searchItemsSQL);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Title</th><th>Description</th><th>Price</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["Title"] . "</td><td>" . $row["Description"] . "</td><td>" . $row["Price"] . "</td></tr>";
        }

        echo "</table>";
    } else {
        echo "No items found for the category: $category";
    }

    // Close the connection
    $conn->close();
}
?>

</body>
</html>
