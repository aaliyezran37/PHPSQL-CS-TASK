<?php

// Database credentials
$servername = "127.0.0.1"; // Hostname/IP address
$username = "root"; // Username
$password = ""; // No password
$dbname = "review"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $rating = $_POST["rating"];
    $review = $_POST["review"];

    // Validate form data
    $nameErr = "";
    $emailErr = "";

    if (empty($name)) {
        $nameErr = "Name is required.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email address.";
    }

    // If there are no errors, proceed to insert data into the database
    if (empty($nameErr) && empty($emailErr)) {

        // Prepare and execute the SQL statement
        $stmt = $conn->prepare("INSERT INTO reviews (name, email, rating, review) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $name, $email, $rating, $review); 

        if ($stmt->execute()) {
            echo "<h2>Review submitted successfully!</h2>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close(); 

    } else {
        // Display error messages to the user
        echo "<p><strong>Errors:</strong></p>";
        if (!empty($nameErr)) {
            echo "<p>$nameErr</p>";
        }
        if (!empty($emailErr)) {
            echo "<p>$emailErr</p>";
        }
    }

}

// Close the database connection
$conn->close();

?>