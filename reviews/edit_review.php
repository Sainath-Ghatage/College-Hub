<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'collegehub');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['review_id'], $_POST['updated_review'])) {
    $review_id = intval($_POST['review_id']);
    $cid = intval($_POST['cid']);
    $user_id = $_SESSION['user_id'];
    $updated_review = trim($_POST['updated_review']);

    $stmt = $conn->prepare("UPDATE reviews SET review = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $updated_review, $review_id, $user_id);
    $stmt->execute();
}

header("Location: share_review.php?cid=$cid");
exit();
?>
