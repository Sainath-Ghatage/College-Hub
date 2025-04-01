<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'collegehub');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT memes.id, memes.image, memes.caption, memes.created_at, users.username 
        FROM memes 
        JOIN users ON memes.user_id = users.id 
        ORDER BY memes.created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $memeId = $row['id'];
        $image = $row['image'];
        $caption = $row['caption'];
        $username = $row['username'];
        $createdAt = date("d M Y, H:i", strtotime($row['created_at']));

        echo "
        <div class='card mb-3'>
            <div class='card-header'>
                <strong>$username</strong> • <small>$createdAt</small>
            </div>
            <div class='card-body'>
                <img src='$image' class='img-fluid' alt='Meme'>
                <p class='mt-2'>$caption</p>
                <button class='btn btn-outline-primary btn-sm like-btn' data-id='$memeId'>👍 Like</button>
                <button class='btn btn-outline-secondary btn-sm comment-toggle' data-id='$memeId'>💬 Comments</button>
                <button class='btn btn-outline-warning btn-sm edit-btn' data-id='$memeId'>✏️ Edit</button>
                <button class='btn btn-outline-danger btn-sm delete-btn' data-id='$memeId'>🗑 Delete</button>

                <!-- Comment Section -->
                <div class='comment-section mt-2' id='comments-$memeId'>
                    <div class='comment-list'>";
        
        $commentSql = "SELECT meme_comments.comment, users.username, meme_comments.created_at 
                       FROM meme_comments 
                       JOIN users ON meme_comments.user_id = users.id 
                       WHERE meme_comments.meme_id = $memeId ORDER BY meme_comments.created_at DESC";
        $commentResult = $conn->query($commentSql);

        if ($commentResult->num_rows > 0) {
            while ($commentRow = $commentResult->fetch_assoc()) {
                echo "<p><strong>{$commentRow['username']}</strong>: {$commentRow['comment']} <small>({$commentRow['created_at']})</small></p>";
            }
        } else {
            echo "<p>No comments yet.</p>";
        }

        echo "</div>
                    <form class='comment-form mt-2' data-id='$memeId'>
                        <input type='text' class='form-control comment-input' placeholder='Write a comment...' required>
                        <button type='submit' class='btn btn-sm btn-primary mt-2'>Post</button>
                    </form>
                </div>
            </div>
        </div>";
    }
} else {
    echo "<p class='text-center'>No memes uploaded yet!</p>";
}
?>
