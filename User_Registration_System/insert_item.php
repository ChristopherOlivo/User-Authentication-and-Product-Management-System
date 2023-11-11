<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // User-specific data
    $userID = 1; // Replace with actual user ID
    $itemsPerDayLimit = 3;

    // Check if the user has posted 3 items today
    $today = date("Y-m-d");
    $checkItemsPerDaySQL = "SELECT COUNT(*) AS posted_count FROM Items WHERE UserID = $userID AND DatePosted = '$today'";
    $postedItemsResult = $conn->query($checkItemsPerDaySQL);
    $postedItemsCount = $postedItemsResult->fetch_assoc()["posted_count"];

    if ($postedItemsCount >= $itemsPerDayLimit) {
        echo "You have already posted $itemsPerDayLimit items today.";
    } else {
        // Insert item into Items table
        $title = $_POST["title"];
        $description = $_POST["description"];
        $price = $_POST["price"];

        $insertItemSQL = "INSERT INTO Items (UserID, Title, Description, Price)
                          VALUES ($userID, '$title', '$description', $price)";

        if ($conn->query($insertItemSQL) === TRUE) {
            echo "Item inserted successfully.";
        } else {
            echo "Error inserting item: " . $conn->error;
        }
    }

    // Close the connection
    $conn->close();
}
?>
