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
    die("❌ Database Connection Failed: " . $conn->connect_error);
}

// Get College ID from URL
if (!isset($_GET['cid']) || !is_numeric($_GET['cid'])) {
    die("❌ Error: Invalid college ID.");
}
$cid = intval($_GET['cid']);

// Fetch College Details
$sql = "SELECT * FROM colleges WHERE cid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cid);
$stmt->execute();
$result = $stmt->get_result();
$college = $result->fetch_assoc();
$stmt->close();

if (!$college) {
    die("❌ Error: College not found.");
}

// Handle New Review Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['review'])) {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $review = trim($_POST['review']);

    if (!empty($review)) {
        $stmt = $conn->prepare("INSERT INTO reviews (cid, user_id, username, review) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $cid, $user_id, $username, $review);
        $stmt->execute();
        $stmt->close();
        header("Location: share_review.php?cid=$cid");
        exit();
    }
}

// Handle Review Edit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_review'])) {
    $review_id = intval($_POST['review_id']);
    $edited_review = trim($_POST['edited_review']);

    if (!empty($edited_review)) {
        $stmt = $conn->prepare("UPDATE reviews SET review = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sii", $edited_review, $review_id, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
        header("Location: share_review.php?cid=$cid");
        exit();
    }
}

// Handle Review Deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_review'])) {
    $review_id = intval($_POST['review_id']);

    $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $review_id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
    header("Location: share_review.php?cid=$cid");
    exit();
}

// Handle Reply Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply']) && isset($_POST['review_id'])) {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $reply = trim($_POST['reply']);
    $review_id = intval($_POST['review_id']);

    if (!empty($reply)) {
        $stmt = $conn->prepare("INSERT INTO replies (review_id, user_id, username, reply) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $review_id, $user_id, $username, $reply);
        $stmt->execute();
        $stmt->close();
        header("Location: share_review.php?cid=$cid");
        exit();
    }
}

// Fetch Reviews
$review_query = "SELECT * FROM reviews WHERE cid = ? ORDER BY datetime DESC";
$review_stmt = $conn->prepare($review_query);
$review_stmt->bind_param("i", $cid);
$review_stmt->execute();
$reviews = $review_stmt->get_result();
$review_stmt->close();

// Fetch Replies
$reply_stmt = $conn->prepare("SELECT * FROM replies WHERE review_id = ? ORDER BY datetime ASC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews for <?php echo htmlspecialchars($college['College_Name']); ?></title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda+SC:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Cardo:ital,wght@0,400;0,700;1,400&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <script>
function editReview(reviewId) {
    document.getElementById("review_text_" + reviewId).style.display = "none";
    document.getElementById("edit_form_" + reviewId).style.display = "block";
}

function cancelEdit(reviewId) {
    document.getElementById("review_text_" + reviewId).style.display = "block";
    document.getElementById("edit_form_" + reviewId).style.display = "none";
}
</script>
    
    <style>
        body {
            font-family:PT Serif, Arial, sans-serif;
        }
        .navbar {
            background-color: #ff9900;
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
    
        }
        .college-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        .review-box, .reply-box {
            background: #222;
            color: white;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .reply-box {
            margin-left: 40px;
            background: #333;
        }
    </style>
</head>
<body class="bg-black text-white">
     <!-- Navigation Bar -->
     <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand p-2" href="../index.html">College <span class="bg-warning text-dark rounded-3">Hub</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../rides/rides.php">SwiftRide</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../reviews/collegeSearch.php">College Reviews</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../memes/memes.php">MemeSphere</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-warning text-dark px-3" href="../login/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-3">
        <div class="bg-warning text-dark p-3 rounded">
            <h2><?php echo htmlspecialchars($college['College_Name']); ?></h2>
            <img src="<?php echo htmlspecialchars($college['College_img']); ?>" class="img-fluid college-img rounded" alt="College Image">
        </div>

        <div class="mt-3">
            <h3>✍️ Add a Review</h3>
            <form method="POST">
                <textarea class="form-control" name="review" rows="3" required placeholder="Share your experience..."></textarea>
                <button type="submit" class="btn btn-warning mt-2">Submit Review</button>
            </form>
        </div>

        <div class="mt-4">
            <h3>📝 Reviews</h3>
            <?php while ($review = $reviews->fetch_assoc()): ?>
                <div class="review-box">
                    <p><strong><?php echo htmlspecialchars($review['username']); ?></strong>
                    <small class="text-muted"><?php echo $review['datetime']; ?></small></p>

                    <div id="review_text_<?php echo $review['id']; ?>">
                        <p><?php echo nl2br(htmlspecialchars($review['review'])); ?></p>
                    </div>

                    <form id="edit_form_<?php echo $review['id']; ?>" method="POST" style="display: none;">
                        <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                        <textarea class="form-control mb-2" name="edited_review"><?php echo htmlspecialchars($review['review']); ?></textarea>
                        <button type="submit" name="edit_review" class="btn btn-success btn-sm">Save</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="cancelEdit(<?php echo $review['id']; ?>)">Cancel</button>
                    </form>

                    <?php if ($review['user_id'] == $_SESSION['user_id']): ?>
                        <button class="btn btn-warning btn-sm mt-2" onclick="editReview(<?php echo $review['id']; ?>)">✏️ Edit</button>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                            <button type="submit" name="delete_review" class="btn btn-danger btn-sm mt-2">🗑️ Delete</button>
                        </form>
                    <?php endif; ?>

                    <form method="POST" class="mt-2">
                        <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                        <textarea class="form-control" name="reply" rows="2" required placeholder="Write a reply..."></textarea>
                        <button type="submit" class="btn btn-primary btn-sm mt-1">Reply</button>
                    </form>

                    <?php
                    $reply_stmt->bind_param("i", $review['id']);
                    $reply_stmt->execute();
                    $reply_result = $reply_stmt->get_result();
                    while ($reply = $reply_result->fetch_assoc()):
                    ?>
                        <div class="reply-box">
                            <p><strong><?php echo htmlspecialchars($reply['username']); ?></strong></p>
                            <p><?php echo nl2br(htmlspecialchars($reply['reply'])); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
