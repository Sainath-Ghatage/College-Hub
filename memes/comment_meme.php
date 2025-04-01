<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'collegehub');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $meme_id = $_POST['meme_id'];
    $comment = htmlspecialchars($_POST['comment']);

    if (!empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO meme_comments (meme_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $meme_id, $user_id, $comment);

        if ($stmt->execute()) {
            echo "Comment added!";
        } else {
            echo "Error adding comment.";
        }
        $stmt->close();
    } else {
        echo "Comment cannot be empty!";
    }
} else {
    echo "Please log in to comment.";
}
?>
