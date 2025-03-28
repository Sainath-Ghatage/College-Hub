<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-black text-white">
    <!--Search-->
    <!-- Search Box -->
<div class="container bg-dark rounded text-warning p-1 mt-1">
    <div class="container">
        <form method="GET" action="collegeSearch.php"> <!-- Add form -->
            <div class="row">
                <div class="col-md-8">
                    <h2>Review Your College Anonymously</h2>
                </div>
                <div class="col-md-3 py-1">
                    <input type="text" class="form-control" name="search" placeholder="Search for a college" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                </div>
                <div class="col-md-1 pt-1">
                    <button class="btn btn-warning" type="submit">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!--Reviews-->
    

<?php
        $conn = new mysqli('localhost','root','','collegehub');
        if($conn->connect_error){
            die("Connection failed:".$conn->connect_error);
        }
        $que = "select * from colleges";
        $result = $conn->query($que);
        if($result->num_rows > 0)
        {
            foreach($result as $res)
            {
                echo "
                    <div class='container bg-dark rounded my-2 '>
                        <div class='row align-items-center py-2'>
                            <div class='col-4 '>
                                <img src='{$res['College_img']}'class='img-fluid rounded my-1' alt='College image'>
                            </div>
                            <div class='col-8 bg-warning rounded my-1'>
                                <div class='p-3'>
                                    <h4 class='text-black'>{$res['College_Name']}</h4>
                                    <h5 class='text-dark'>{$res['College_Address']}</h5>
                                    <p class='text-dark'>Phone No.: {$res['College_phoneNo']}</p>
                                    <p class='text-dark'>Email: {$res['College_email']}</p>
                                    <p class='text-dark'>Website: <a href='{$res['College_website']}' >{$res['College_website']}</a></p>
                                    <a class='btn btn-primary' href='{$res['review_page']}'>Read Reviews</a>
                                </div>
                            </div>
                        </div>
                    </div>
                ";
            }
        }

        $conn->close();
    ?>




    <script src="js/bootstrap.bundle.js"></script>
</body>
</html>