<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Database Connection
    $conn = new mysqli('localhost', 'root', '', 'collegehub');

    if ($conn->connect_error) {
        die("❌ Database Connection Failed: " . $conn->connect_error);
    }

    // Sanitize and retrieve form data
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $country_code = $conn->real_escape_string($_POST['country_code']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $message = $conn->real_escape_string($_POST['message']);

    // Insert data into the database
    $sql = "INSERT INTO contact_messages (first_name, last_name, email, country_code, phone_number, message) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $first_name, $last_name, $email, $country_code, $phone_number, $message);

    if ($stmt->execute()) {
        echo "✅ Message sent successfully!";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
