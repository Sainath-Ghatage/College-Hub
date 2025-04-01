<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'collegehub');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $meme_id = $_POST['meme_id'];

    // Check if the meme belongs to the logged-in user
    $stmt = $conn->prepare("SELECT user_id, image FROM memes WHERE id = ?");
    $stmt->bind_param("i", $meme_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $meme = $result->fetch_assoc();

    if ($meme && $meme['user_id'] == $user_id) {
        $imagePath = $meme['image'];

        // Delete related likes and comments first
        $conn->query("DELETE FROM meme_likes WHERE meme_id = $meme_id");
        $conn->query("DELETE FROM meme_comments WHERE meme_id = $meme_id");

        // Delete meme from the database
        $deleteStmt = $conn->prepare("DELETE FROM memes WHERE id = ?");
        $deleteStmt->bind_param("i", $meme_id);

        if ($deleteStmt->execute()) {
            // Remove meme image from the server
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            echo "Meme deleted!";
        } else {
            echo "Error deleting meme.";
        }
        $deleteStmt->close();
    } else {
        echo "You can only delete your own memes.";
    }
} else {
    echo "Invalid request.";
}
?>
