<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Hub</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda+SC:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Cardo:ital,wght@0,400;0,700;1,400&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>
<body class=" text-white ">    

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">College <span class="bg-warning text-dark rounded-4 p-2">Hub</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#contactus">Contact Us</a></li> <!-- Fixed Link -->
                <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
                    <li class="nav-item"><a class="btn btn-danger" href="login/logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn btn-success" href="login/login.html">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

    <!-- Carousel -->
    <div id="featureCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2500">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/carousel1.svg" class="d-block w-100" alt="Feature 1"  style="height: 600px; object-fit: cover;">
                <div class="carousel-caption d-md-block bg-dark bg-opacity-75 p-3 rounded">
                    <h5 class="text-warning">SwiftRide - College Carpooling</h5>
                    <p>Find and share rides with fellow students to save money and make friends.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="images/carousel2.svg" class="d-block w-100" alt="Feature 2"  style="height: 600px; object-fit: cover;">
                <div class="carousel-caption d-md-block bg-dark bg-opacity-75 p-3 rounded">
                    <h5 class="text-warning">CampusVoice - Anonymous Reviews</h5>
                    <p>Share your college experience anonymously and help future students.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="images/carousel3.svg" class="d-block w-100" alt="Feature 3"  style="height: 600px; object-fit: cover;">
                <div class="carousel-caption d-md-block bg-dark bg-opacity-75 p-3 rounded">
                    <h5 class="text-warning">MemeSphere - College Memes</h5>
                    <p>Express your campus life through funny memes and connect with students.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#featureCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#featureCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <br>
    <hr class="featurette-divider container">


<!--Feature section-->
        <section id="features" class="container text-center mt-5">
            <h2 class="text-warning">Our Features</h2>
            <p class="text-warning">Explore the amazing features of College Hub.</p>
            <div class="row mt-4">
                <div class="col-md-4 mt-2" id="animate">
                    <div class="card">
                        <img src="images/img2.svg" class="card-img-top" alt="Reviews">
                        <div class="card-body bg-warning">
                            <h4 class="card-title">Reviews</h4>
                            <p class="card-text">Share your experience anonymously and help others make informed decisions.</p>
                            <a href="reviews/collegeSearch.php" class="btn btn-primary" target="#">Explore Reviews</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-2" id="animate">
                    <div class="card">
                        <img src="images/img1.svg" class="card-img-top" alt="Ride Share">
                        <div class="card-body bg-warning">
                            <h4 class="card-title">Ride Share</h4>
                            <p class="card-text">Carpool with fellow students, save money, and make new friends.</p>
                            <a href="rides/rides.php" class="btn btn-primary" target="#">Find a Ride</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-2" id="animate">
                    <div class="card">
                        <img src="images/img3.svg" class="card-img-top" alt="Memes">
                        <div class="card-body bg-warning">
                            <h4 class="card-title">Memes</h4>
                            <p class="card-text">Laugh and create trending memes from your campus life.</p>
                            <a href="memes/memes.php" class="btn btn-primary" target="#">View Memes</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <br>
    <hr class="featurette-divider container">

    <!--About Section-->
    <section id="about" class="container text-center mt-5">
        <h2 class="text-warning">About College Hub</h2>  
        <br> <br>
        <div class="container">
            <div class="row bg-warning rounded-4" id="animate">
                <div class="col-md-7  text-black p-5">
                    <h1>Anonymous College Reviews</h1>
                    <p>Our College Reviews feature allows students to share their experiences without revealing their identity. Users can rate their college on factors like academics, campus life, faculty, and placements. The platform ensures anonymity, so you can be honest without hesitation. Simply log in, choose your college, and submit a review—it helps future students make better choices!</p>
                </div>
                <div class="col-md-5">
                    <img src="images/img1.svg" class="rounded-5 p-3" alt="">
                </div>
            </div>
        </div>

        <br>
        <hr class="featurette-divider container">
        <br>

        <div class="container">
            <div class="row bg-warning rounded-4" id="animate">
                <div class="col-md-5">
                    <img src="images/img2.svg" class="rounded-5 p-3" alt="">
                </div>
                <div class="col-md-7  text-black p-5">
                    <h1>Ride Sharing for Students</h1>
                    <p>The Ride Share feature connects students looking for carpooling options. Whether you need a ride or have extra space in your vehicle, just enter your pickup location, destination, and preferred time. Our system will match you with fellow students traveling the same route, helping you save money and reduce your carbon footprint!</p>
                </div>
            </div>
        </div>

        <br>
        <hr class="featurette-divider container">
        <br>

        <div class="container">
            <div class="row bg-warning rounded-4" id="animate">
                <div class="col-md-7 text-black p-5">
                    <h1>College Meme Hub</h1>
                    <p>The Meme Hub lets students create and share funny, relatable memes about college life. You can upload images, add text, and customize fonts with our easy-to-use meme generator. Engage with others by liking, sharing, and commenting on trending memes from your campus. It’s the perfect way to unwind and bond with fellow students over humor!</p>
                </div>
                <div class="col-md-5">
                    <img src="images/img3.svg" class="rounded-5 p-3" alt="">
                </div>
            </div>
        </div>
    </section>

    <br>
    <hr class="featurette-divider container">
    <br>

    <!--Contact Us Section-->

  <!-- Contact Us Section -->
<section id="contact" class="py-5">
    <div class="container bg-warning text-black rounded-4 shadow-lg p-4" id="animate">
        <div class="row">
            <!-- Left Section -->
            <div class="col-lg-6 mb-4">
                <h1 class="fw-bold">Contact Us</h1>
                <p class="lead">Email, call, or complete the form to learn how Snappy can solve your messaging problem.</p>
                
                <p class="fw-bold"><i class="bi bi-envelope-fill me-2"></i> CollegeHub@gmail.com</p>
                <p class="fw-bold"><i class="bi bi-telephone-fill me-2"></i> 321-221-231</p>
                
                <div class="social-icons my-3">
                    <a href="https://facebook.com" target="_blank" class="me-3"><i class="bi bi-facebook fs-4"></i></a>
                    <a href="https://twitter.com" target="_blank" class="me-3"><i class="bi bi-twitter fs-4"></i></a>
                    <a href="https://instagram.com" target="_blank" class="me-3"><i class="bi bi-instagram fs-4"></i></a>
                    <a href="https://linkedin.com" target="_blank" class="me-3"><i class="bi bi-linkedin fs-4"></i></a>
                    <a href="https://youtube.com" target="_blank"><i class="bi bi-youtube fs-4"></i></a>
                </div>

                <h5 class="fw-bold">Customer Support</h5>
                <p>Our support team is available 24/7 to assist you with any concerns.</p>

                <h5 class="fw-bold">Feedback & Suggestions</h5>
                <p>We value your input and are constantly working to improve College Hub.</p>

                <h5 class="fw-bold">Media Inquiries</h5>
                <p>For press-related inquiries, contact us at <a href="mailto:CollegeHub@gmail.com" class="fw-bold text-decoration-none">CollegeHub@gmail.com</a>.</p>
            </div>

            <!-- Right Section (Form) -->
            <div class="col-lg-6">
                <div class="bg-white text-black rounded-4 p-4 shadow">
                    <h3 class="fw-bold">Get in Touch</h3>
                    <p>You can reach us anytime</p>
                    <form action="feedback.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="first_name" class="form-control" placeholder="First name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="last_name" class="form-control" placeholder="Last name" required>
                            </div>
                        </div>
                        <input type="email" name="email" class="form-control my-3" placeholder="Your email" required>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <select class="form-select" name="country_code" required>
                                    <option value="91" selected>+91</option>
                                    <option value="62">+62</option>
                                    <option value="1">+1</option>
                                    <option value="44">+44</option>
                                </select>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="phone_number" placeholder="Phone number" required>
                            </div>
                        </div>
                        <textarea class="form-control my-3" name="message" rows="3" placeholder="How can we help?" maxlength="250" required></textarea>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Submit</button>
                        <p class="mt-3 text-muted text-center">
                            By contacting us, you agree to our <a href="#" class="fw-bold text-decoration-none">Terms of Service</a> and 
                            <a href="#" class="fw-bold text-decoration-none">Privacy Policy</a>.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


    <br>
    <hr class="featurette-divider container">
    <footer class="container-fluid text-center mt-5">
        <p><a href="#">Back to top</a></p>
        <p>&copy; <script>document.write(new Date().getFullYear());</script> College Hub</p>
    </footer>
<script src="js/bootstrap.bundle.js"></script>
<script src="js/script.js"></script>
</body>
</html>
