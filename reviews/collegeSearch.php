<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda+SC:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Cardo:ital,wght@0,400;0,700;1,400&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <style>
        .college-img {
            width: 100%;
            height: 200px; /* Fixed height */
            object-fit: cover; /* Ensures uniform proportions */
        }
        .text-wrap {
            word-break: break-word;
        }
    </style>
</head>
<body class="bg-black text-white">
    <!-- Search Box -->
    <div class="container bg-dark rounded text-warning p-2 mt-2">
        <div class="container">
            <form method="GET" action="collegeSearch.php">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2>Review Your College Anonymously</h2>
                    </div>
                    <div class="col-md-4 d-flex justify-content-end align-items-center">
                        <input type="text" class="form-control me-2" name="search" placeholder="Search for a college" 
                            value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                        <button class="btn btn-warning" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reviews Section -->
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
                <div class='container bg-warning rounded my-2'>
                    <div class='row align-items-center py-2'>
                        <div class='col-md-4'>
                            <img src='{$res['College_img']}' class='img-fluid rounded college-img' alt='College image'>
                        </div>
                        <div class='col-md-8 my-1 p-3 text-dark'>
                            <h4>{$res['College_Name']}</h4>
                            <h5>{$res['College_Address']}</h5>
                            <p>Phone No.: {$res['College_phoneNo']}</p>
                            <p>Email: {$res['College_email']}</p>
                            <p>Website: <a href='{$res['College_website']}' class='text-wrap'>{$res['College_website']}</a></p>
                            <a class='btn btn-primary' href='share_review.php?cid={$res['cid']}'>Read Reviews</a>
                        </div>
                    </div>
                </div>
            ";
        }
    } else {
        echo "<div class='container bg-dark text-warning rounded p-3 my-2'><h4>No colleges found matching your search.</h4></div>";
    }
    
    $conn->close();
    ?>

    <footer class="container-fluid text-center mt-5">
        <p><a href="#">Back to top</a></p>
        <p>&copy; <script>document.write(new Date().getFullYear());</script> College Hub</p>
    </footer>
    
    <script src="../js/bootstrap.bundle.js"></script>
</body>
</html>