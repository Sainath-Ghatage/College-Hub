<?php
session_start();
$conn = new mysqli("localhost", "root", "", "collegehub");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username']; // Logged-in user's username

// Handle Ride Request Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_ride'])) {
    $date_time = $_POST['date_time'];
    $pickup = $_POST['pickup'];
    $drop = $_POST['drop'];
    $description = $_POST['description'] ?? '';

    $stmt = $conn->prepare("INSERT INTO rides (user_id, username, date_time, pickup, dropoff, description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $_SESSION['user_id'], $username, $date_time, $pickup, $drop, $description);
    $stmt->execute();
    $stmt->close();
}

// Handle Ride Acceptance
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accept_ride'])) {
    $ride_id = $_POST['ride_id'];
    $pickup_point = $_POST['pickup_point'];

    $stmt = $conn->prepare("UPDATE rides SET driver_id=?, driver_username=?, driver_pickup=? WHERE id=?");
    $stmt->bind_param("issi", $_SESSION['user_id'], $username, $pickup_point, $ride_id);
    $stmt->execute();
    $stmt->close();
}

// Handle Ride Cancellation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_ride'])) {
    $ride_id = $_POST['ride_id'];

    $stmt = $conn->prepare("UPDATE rides SET driver_id=NULL, driver_username=NULL, driver_pickup=NULL WHERE id=?");
    $stmt->bind_param("i", $ride_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all ride requests
$rides = $conn->query("SELECT * FROM rides ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftRide | College Hub</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda+SC:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Cardo:ital,wght@0,400;0,700;1,400&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: PT Serif , Arial, sans-serif;
            background-color: #181818; /* Dark mode */
            color: white;
        }
        .navbar {
            background-color: #ff9900;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .btn-warning {
            background-color: #ff9900;
            border: none;
        }
        .btn-warning:hover {
            background-color: #e68a00;
        }
        .card {
            background-color: #282828;
            color: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.1);
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: scale(1.02);
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

    <div class="container mt-5">
        <h2 class="text-center text-warning">SwiftRide - Share Your Ride</h2>

        <!-- Ride Request Form -->
        <div class="card p-4 mt-4">
            <h4 class="mb-3">Request a Ride</h4>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Date & Time:</label>
                    <input type="datetime-local" name="date_time" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pickup Location:</label>
                    <input type="text" name="pickup" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Drop Location:</label>
                    <input type="text" name="drop" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description (Optional):</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <button type="submit" name="request_ride" class="btn btn-warning w-100">Request Ride</button>
            </form>
        </div>

        <h3 class="mt-5 text-warning">Available Ride Requests</h3>

        <!-- Display Ride Requests -->
        <div class="row">
            <?php while ($ride = $rides->fetch_assoc()): ?>
                <div class="col-md-6">
                    <div class="card p-3 mt-3">
                        <h5>Requested by: <span class="text-warning"><?= htmlspecialchars($ride['username']) ?></span></h5>
                        <p><strong>📅 Date & Time:</strong> <?= $ride['date_time'] ?></p>
                        <p><strong>📍 Pickup:</strong> <?= htmlspecialchars($ride['pickup']) ?></p>
                        <p><strong>📍 Drop:</strong> <?= htmlspecialchars($ride['dropoff']) ?></p>
                        <?php if ($ride['description']): ?>
                            <p><strong>📝 Description:</strong> <?= htmlspecialchars($ride['description']) ?></p>
                        <?php endif; ?>

                        <!-- Ride Status -->
                        <?php if ($ride['driver_id']): ?>
                            <p class="text-warning"><strong>✅ Accepted by:</strong> <?= htmlspecialchars($ride['driver_username']) ?> (Pickup: <?= htmlspecialchars($ride['driver_pickup']) ?>)</p>
                        <?php else: ?>
                            <form method="post" class="mt-2">
                                <input type="hidden" name="ride_id" value="<?= $ride['id'] ?>">
                                <label>Enter Pickup Point:</label>
                                <input type="text" name="pickup_point" class="form-control mb-2" required>
                                <button type="submit" name="accept_ride" class="btn btn-primary w-100">Accept Ride</button>
                            </form>
                        <?php endif; ?>

                        <!-- Cancel Ride Option -->
                        <?php if ($ride['driver_id'] == $_SESSION['user_id'] || $ride['user_id'] == $_SESSION['user_id']): ?>
                            <form method="post" class="mt-2">
                                <input type="hidden" name="ride_id" value="<?= $ride['id'] ?>">
                                <button type="submit" name="cancel_ride" class="btn btn-danger w-100">Cancel Ride</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

    </div>

    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
