<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "collegehub");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["usrname"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Secure password hashing

    // Check if user already exists
    $checkUser = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkUser->bind_param("s", $email);
    $checkUser->execute();
    $result = $checkUser->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already registered! Try logging in.'); window.location.href='register.html';</script>";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            echo "<script>alert('Registration Successful! Please Login.'); window.location.href='login.html';</script>";
        } else {
            echo "<script>alert('Registration Failed. Try Again!'); window.location.href='register.html';</script>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
