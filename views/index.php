<?php
require_once '../util/functions.php';
session_start();
// dumpAndDie('Here');

if (!isset($_SESSION['role'])) {
    header('Location: /tour/views/login');
    exit();
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

    <?php if ($role == 'admin'): ?>
        <h2>Admin Features</h2>
        <ul>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="manage_tours.php">Manage Tours</a></li>
            <li><a href="view_reports.php">View Reports</a></li>
        </ul>
    <?php endif; ?>

    <?php if ($role == 'tour_agent'): ?>
        <h2>Tour Agent Features</h2>
        <ul>
            <li><a href="create_tour.php">Create Tour</a></li>
            <li><a href="manage_tours.php">Manage My Tours</a></li>
            <li><a href="view_bookings.php">View Bookings</a></li>
        </ul>
    <?php endif; ?>

    <?php if ($role == 'user'): ?>
        <h2>User Features</h2>
        <ul>
            <li><a href="view_tours.php">View Tours</a></li>
            <li><a href="my_bookings.php">My Bookings</a></li>
            <li><a href="edit_profile.php">Edit Profile</a></li>
        </ul>
    <?php endif; ?>

    <a href="logout.php">Logout</a>
</body>
</html>