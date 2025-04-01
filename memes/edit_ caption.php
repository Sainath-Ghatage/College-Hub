<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'collegehub');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $meme_id = $_POST['meme_id'];
    $new_caption = htmlspecialchars($_POST['new_caption']);

    // Check if the meme belongs to the logged-in user
    $stmt = $conn->prepare("SELECT user_id FROM memes WHERE id = ?");
    $stmt->bind_param("i", $meme_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $meme = $result->fetch_assoc();

    if ($meme && $meme['user_id'] == $user_id) {
        // Update caption
        $updateStmt = $conn->prepare("UPDATE memes SET caption = ? WHERE id = ?");
        $updateStmt->bind_param("si", $new_caption, $meme_id);

        if ($updateStmt->execute()) {
            echo "Caption updated!";
        } else {
            echo "Error updating caption.";
        }
        $updateStmt->close();
    } else {
        echo "You can only edit your own memes.";
    }
} else {
    echo "Invalid request.";
}
?>
