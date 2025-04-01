<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Reviews | College Hub</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #181818; /* Dark mode */
            color: white;
            font-family: 'PT Serif', serif;
        }
        .navbar {
            background-color: #ff9900;
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
    
        }
        .btn-warning {
            background-color: #ff9900;
            border: none;
        }
        .btn-warning:hover {
            background-color: #e68a00;
        }
        .college-img {
            width: 100%;
            height: 200px; /* Fixed height */
            object-fit: cover; /* Ensures uniform proportions */
            border-radius: 10px;
        }
        .card {
            background-color: #282828;
            color: white;
            border-radius: 10px;
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: scale(1.02);
            box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.1);
        }
        .text-wrap {
            word-break: break-word;
        }
    </style>
</head>
<body>
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

    <!-- Search Box -->
    <div class="container bg-dark rounded text-warning p-3 mt-4">
        <form method="GET" action="collegeSearch.php">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2>📢 Review Your College Anonymously</h2>
                </div>
                <div class="col-md-4 d-flex justify-content-end align-items-center">
                    <input type="text" class="form-control me-2 rounded-pill" name="search" 
                        placeholder="🔍 Search for a college..." 
                        value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    <button class="btn btn-warning rounded-pill" type="submit">Search</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Reviews Section -->
    <div class="container mt-4">
        <div class="row">
            <?php
            $conn = new mysqli('localhost', 'root', '', 'collegehub');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
            $query = !empty($search) ? 
                "SELECT * FROM colleges WHERE College_Name LIKE '%$search%' OR College_Address LIKE '%$search%'" : 
                "SELECT * FROM colleges";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                foreach ($result as $res) {
                    echo "
                        <div class='col-md-6'>
                            <div class='card p-3 mt-3'>
                                <div class='row'>
                                    <div class='col-md-5'>
                                        <img src='{$res['College_img']}' class='img-fluid college-img' alt='College image'>
                                    </div>
                                    <div class='col-md-7 my-1 p-3'>
                                        <h4 class='text-warning'>{$res['College_Name']}</h4>
                                        <p><strong>📍 Address:</strong> {$res['College_Address']}</p>
                                        <p><strong>📞 Phone:</strong> {$res['College_phoneNo']}</p>
                                        <p><strong>📧 Email:</strong> {$res['College_email']}</p>
                                        <p><strong>🌐 Website:</strong> <a href='{$res['College_website']}' class='text-warning text-wrap'>{$res['College_website']}</a></p>
                                        <a class='btn btn-primary w-100' href='share_review.php?cid={$res['cid']}'>Read Reviews</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ";
                }
            } else {
                echo "<div class='container bg-dark text-warning rounded p-3 my-2 text-center'>
                        <h4>❌ No colleges found matching your search.</h4>
                      </div>";
            }

            $conn->close();
            ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="container-fluid text-center mt-5 p-3">
        <p><a href="#" class="text-warning">⬆ Back to top</a></p>
        <p>&copy; <script>document.write(new Date().getFullYear());</script> College Hub</p>
    </footer>

    <script src="../js/bootstrap.bundle.js"></script>
</body>
</html>
