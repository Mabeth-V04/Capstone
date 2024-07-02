<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $name = htmlspecialchars($_POST['name']);
    $address = htmlspecialchars($_POST['address']);
    $contact = htmlspecialchars($_POST['contact']);
    $email = htmlspecialchars($_POST['email']);
    $passwords = htmlspecialchars($_POST['password']);

    // Validate form data
    if (empty($name) || empty($address) || empty($contact) || empty($email) || empty($passwords)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit;
    }

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = ""; // Make sure this is correct
    $dbname = "cap";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('This email is already registered.'); window.history.back();</script>";
        $stmt->close();
        $conn->close();
        exit;
    }

    $stmt->close();

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (name, address, contact, username, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $address, $contact, $email, $passwords);

    // Execute statement
    if ($stmt->execute()) {
        echo "<!DOCTYPE html>
              <html>
              <head>
                <title>Success</title>
                <style>
                  .notification {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background-color: #4CAF50;
                    color: white;
                    padding: 20px;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                  }
                </style>
              </head>
              <body>
                <div class='notification'>Signup Successful</div>
                <script type='text/javascript'>
                  setTimeout(function(){
                    window.location.href = 'sign-in.php';
                  }, 2000); // Redirect after 2 seconds
                </script>
              </body>
              </html>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>
