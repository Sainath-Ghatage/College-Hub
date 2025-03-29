<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'collegehub');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available rides
$rides_sql = "SELECT rides.*, users.username FROM rides JOIN users ON rides.user_id = users.id ORDER BY rides.ride_time ASC";
$rides_result = $conn->query($rides_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Rides</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="bg-dark text-white">
    <div class="container mt-4">
        <h2 class="text-warning">Available Rides</h2>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Pickup Location</th>
                    <th>Time</th>
                    <th>Vehicle</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($ride = $rides_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ride['username']); ?></td>
                        <td><?php echo htmlspecialchars($ride['pickup_location']); ?></td>
                        <td><?php echo htmlspecialchars($ride['ride_time']); ?></td>
                        <td><?php echo htmlspecialchars($ride['vehicle_details']); ?></td>
                        <td>
                            <?php if ($_SESSION['user_id'] != $ride['user_id']): ?>
                                <a href="request_ride.php?ride_id=<?php echo $ride['id']; ?>" class="btn btn-warning">Request</a>
                            <?php else: ?>
                                <span class="text-muted">Your Ride</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="../js/bootstrap.bundle.js"></script>
</body>
</html>
<?php
$conn->close();
?>
