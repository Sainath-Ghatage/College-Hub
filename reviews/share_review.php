<?php
// Start session to get logged-in user info
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Database Connection
$conn = new mysqli('localhost', 'root', '', 'collegehub');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get College ID
if (!isset($_GET['cid'])) {
    die("Invalid request.");
}
$cid = intval($_GET['cid']);

// Fetch College Details
$sql = "SELECT * FROM colleges WHERE cid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cid);
$stmt->execute();
$college = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle Review Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['review'])) {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username']; // Use username from session
    $review = trim($_POST['review']);

    if (!empty($review)) {
        $stmt = $conn->prepare("INSERT INTO reviews (cid, user_id, username, review) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $cid, $user_id, $username, $review);
        $stmt->execute();
        $stmt->close();
        header("Location: share_review.php?cid=$cid"); // Refresh page
        exit();
    }
}

// Handle Reply Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply']) && isset($_POST['review_id'])) {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username']; // Use username from session
    $reply = trim($_POST['reply']);
    $review_id = intval($_POST['review_id']);

    if (!empty($reply)) {
        $stmt = $conn->prepare("INSERT INTO replies (review_id, user_id, username, reply) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $review_id, $user_id, $username, $reply);
        $stmt->execute();
        $stmt->close();
        header("Location: share_review.php?cid=$cid"); // Refresh page
        exit();
    }
}

// Fetch Reviews
$reviews = $conn->query("SELECT * FROM reviews WHERE cid = $cid ORDER BY datetime DESC");

// Fetch Replies (Nested Query)
$replies = [];
$reply_stmt = $conn->prepare("SELECT * FROM replies WHERE review_id = ? ORDER BY datetime ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews for <?php echo htmlspecialchars($college['College_Name']); ?></title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda+SC:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Cardo:ital,wght@0,400;0,700;1,400&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <style>
        .college-img { width: 100%; height: 250px; object-fit: cover; }
        .review-box, .reply-box { background: #222; color: white; padding: 10px; margin-bottom: 10px; border-radius: 5px; }
        .reply-box { margin-left: 40px; background: #333; }
    </style>
</head>
<body class="bg-black text-white">
    <div class="container mt-3">
        <!-- College Details -->
        <div class="bg-warning text-dark p-3 rounded">
            <h2><?php echo htmlspecialchars($college['College_Name']); ?></h2>
            <p><?php echo htmlspecialchars($college['College_Address']); ?></p>
            <img src="<?php echo htmlspecialchars($college['College_img']); ?>" class="img-fluid college-img rounded" alt="College Image">
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($college['College_phoneNo']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($college['College_email']); ?></p>
            <p><strong>Website:</strong> <a href="<?php echo htmlspecialchars($college['College_website']); ?>" target="_blank"><?php echo htmlspecialchars($college['College_website']); ?></a></p>
        </div>

        <!-- Add Review -->
        <div class="mt-3">
            <h3>Add a Review</h3>
            <form method="POST">
                <textarea class="form-control" name="review" rows="3" required></textarea>
                <button type="submit" class="btn btn-warning mt-2">Submit Review</button>
            </form>
        </div>

        <!-- Display Reviews -->
        <div class="mt-4">
            <h3>Reviews</h3>
            <?php while ($review = $reviews->fetch_assoc()): ?>
                <div class="review-box">
                    <p><strong><?php echo htmlspecialchars($review['username']); ?></strong> <small class="text-muted"><?php echo $review['datetime']; ?></small></p>
                    <p><?php echo nl2br(htmlspecialchars($review['review'])); ?></p>

                    <!-- Show Replies -->
                    <?php
                    $reply_stmt->bind_param("i", $review['id']);
                    $reply_stmt->execute();
                    $reply_result = $reply_stmt->get_result();
                    while ($reply = $reply_result->fetch_assoc()):
                    ?>
                        <div class="reply-box">
                            <p><strong><?php echo htmlspecialchars($reply['username']); ?></strong> <small class="text-muted"><?php echo $reply['datetime']; ?></small></p>
                            <p><?php echo nl2br(htmlspecialchars($reply['reply'])); ?></p>
                        </div>
                    <?php endwhile; ?>

                    <!-- Reply Form -->
                    <form method="POST" class="mt-2">
                        <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                        <textarea class="form-control" name="reply" rows="2" required placeholder="Write a reply..."></textarea>
                        <button type="submit" class="btn btn-primary btn-sm mt-1">Reply</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="../js/bootstrap.bundle.js"></script>
</body>
</html>
<?php
$reply_stmt->close();
$conn->close();
?>
