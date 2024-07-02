<?php
// Start the session
session_start();

// Function to sanitize input data
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection file
    // include 'db_connection.php'; // Uncomment this line if you have a separate file for database connection
    
    // Database credentials
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cap";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get email and password from POST request and sanitize input
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);

    // SQL query to check user credentials
    $sql = "SELECT * FROM users WHERE username = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        
        // Redirect to dashboard or another page
        header("Location: dashboard.html");
        exit;
    } else {
        // User not found, display an error message
        $error_message = "Invalid email or password";
        echo "<script type='text/javascript'>alert('$error_message'); window.location.href = 'sign-in.php';</script>";
    }

    // Close connection
    $conn->close();
} else {
    // Redirect to sign-in page if not a POST request
    header("Location: sign-in.php");
    exit;
}
?>
