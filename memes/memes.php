<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'collegehub');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!isset($_SESSION['user_id'])) {
    echo "<p class='text-center text-danger'>Please <a href='login.php'>log in</a> to post and interact with memes.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memes | College Hub</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda+SC:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Cardo:ital,wght@0,400;0,700;1,400&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom CSS -->
    <style>
        body {
            font-family: PT Serif , Arial, sans-serif;
            background-color: #181818; /* Dark theme */
            color: white;
        }
        .navbar {
            background-color: #ff9900; /* Pornhub Orange */
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #ff9900;
            border: none;
        }
        .btn-primary:hover {
            background-color: #e68a00;
        }
        .card {
            background-color: #282828;
            color: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.1);
        }
        .card img {
            max-height: 400px;
            object-fit: contain;
            border-radius: 10px;
        }
        .comment-section {
            display: none;
            background-color: #333;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
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
    <!-- Main Container -->
    <div class="container mt-4">
        <h2 class="text-center text-warning">Share Your Memes</h2>

        <!-- Meme Upload Form -->
        <div class="card p-4 mt-3">
            <form id="uploadMemeForm" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Select Meme Image:</label>
                    <input type="file" class="form-control" name="memeImage" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Caption:</label>
                    <input type="text" class="form-control" name="caption" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Upload Meme</button>
            </form>
        </div>

        <!-- Meme Feed -->
        <div id="memeFeed" class="mt-4"></div>
    </div>

    <script src="script.js"></script>
</body>
</html>
