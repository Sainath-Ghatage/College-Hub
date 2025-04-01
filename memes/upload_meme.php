<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'collegehub');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}   
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to upload memes.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["memeImage"])) {
    $user_id = $_SESSION['user_id'];
    $caption = htmlspecialchars($_POST['caption']);

    // Ensure upload directory exists
    $target_dir = __DIR__ . "/memes/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $imageFileType = strtolower(pathinfo($_FILES["memeImage"]["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . time() . "_" . basename($_FILES["memeImage"]["name"]);

    // Allowed file types
    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_types)) {
        die("Only JPG, JPEG, PNG, and GIF files are allowed.");
    }

    // Move uploaded file
    if (move_uploaded_file($_FILES["memeImage"]["tmp_name"], $target_file)) {
        // Save relative path to DB
        $relative_path = "memes/" . time() . "_" . basename($_FILES["memeImage"]["name"]);

        $stmt = $conn->prepare("INSERT INTO memes (user_id, image, caption, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $user_id, $relative_path, $caption);

        if ($stmt->execute()) {
            echo "Meme uploaded successfully!";
        } else {
            echo "Database error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "Invalid request.";
}
?>
