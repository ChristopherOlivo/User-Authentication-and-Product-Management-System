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

    // User-specific data (you might need to get the user ID based on the session)
    $userID = 1; // Replace with actual user ID

    // Review-specific data
    $itemID = $_POST["item"];
    $rating = $_POST["rating"];
    $description = $_POST["description"];

    // Check if the user has given 3 reviews today 
    $today = date("Y-m-d");
    $checkItemsPerDaySQL = "SELECT COUNT(*) AS posted_count FROM Items WHERE UserID = $userID AND DatePosted = '$today'";
    $postedItemsResult = $conn->query($checkItemsPerDaySQL);
    $postedItemsCount = $postedItemsResult->fetch_assoc()["posted_count"];

    if ($postedItemsCount >= $itemsPerDayLimit) {
        echo "You have already posted $itemsPerDayLimit items today.";
    } else {
    
        // Check if the user is reviewing their own item
    $checkOwnItemSQL = "SELECT UserID FROM Items WHERE ItemID = $itemID";
    $ownItemResult = $conn->query($checkOwnItemSQL);
    $itemUserID = $ownItemResult->fetch_assoc()["UserID"];

    if ($itemUserID == $userID) {
        echo "You cannot review your own item.";
    } else {
        // Insert review into Reviews table
        $insertReviewSQL = "INSERT INTO Reviews (UserID, ItemID, Rating, Description)
                            VALUES ($userID, $itemID, '$rating', '$description')";

        if ($conn->query($insertReviewSQL) === TRUE) {
            echo "Review submitted successfully.";
        } else {
            echo "Error submitting review: " . $conn->error;
        }
    }

    // Close the connection
    $conn->close();
}
?>
