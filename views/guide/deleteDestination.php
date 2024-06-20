<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'tour_guide') {
    header('Location: /views/login.php');
    exit();
}

$servername = "localhost";
$username = "teme";
$password = "12345678";
$dbname = "tour";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['id'])) {
    $destination_id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM destinations WHERE id = ?");
    $stmt->bind_param('i', $destination_id);

    if ($stmt->execute()) {
        echo "Destination deleted successfully!";
        header('Refresh: 2; URL=/tour/views/guide/guideDestinations.php');
        exit();
    } else {
        echo "Error deleting destination: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Destination ID not provided.";
}

$conn->close();
