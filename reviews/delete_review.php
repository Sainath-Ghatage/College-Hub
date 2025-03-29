<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'collegehub');

if (isset($_GET['review_id'], $_GET['cid'])) {
    $review_id = intval($_GET['review_id']);
    $cid = intval($_GET['cid']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $review_id, $user_id);
    $stmt->execute();
}

header("Location: share_review.php?cid=$cid");
exit();
?>
