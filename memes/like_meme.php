<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'collegehub');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $meme_id = $_POST['meme_id'];

    // Check if the user has already liked the meme
    $checkLike = $conn->prepare("SELECT * FROM meme_likes WHERE meme_id = ? AND user_id = ?");
    $checkLike->bind_param("ii", $meme_id, $user_id);
    $checkLike->execute();
    $result = $checkLike->get_result();

    if ($result->num_rows == 0) {
        // Insert like into database
        $stmt = $conn->prepare("INSERT INTO meme_likes (meme_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $meme_id, $user_id);

        if ($stmt->execute()) {
            echo "Liked";
        } else {
            echo "Error liking meme.";
        }
        $stmt->close();
    } else {
        echo "Already liked";
    }
} else {
    echo "Please log in to like memes.";
}
?>
   