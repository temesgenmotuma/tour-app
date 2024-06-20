<?php
session_start();

// echo $_SESSION['id']??"no";
// die();


//TODO SESSION NOT BEING SET FIX
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'tour_guide') {
    header('Location: /tour/views/login');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name = $_POST['name'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $best_time = $_POST['best_time'];
    $accessibility = $_POST['accessibility'];
    $imagePath = $_POST['image'];

   //TODO FIX FILE UPLOAD 
    // Process the uploaded image
   /*  $image = $_FILES['image'];
    $imagePath = 'uploads/' . basename($image['name']);
    
    if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
        die("Error uploading image.");
    } */

    
    $conn = new mysqli('localhost', 'teme', '12345678', 'tour');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $tour_guide_id = $_SESSION['id'];

    // Insert the destination into the database
    $stmt = $conn->prepare("INSERT INTO destinations (name, description, location, image_url, category, best_season,accessibility, tour_guide_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssssssi', $name, $description, $location, $imagePath, $category, $best_time, $accessibility,$tour_guide_id);

    if ($stmt->execute()) {
        echo "<div>Destination created successfully!</div>";
        header('Refresh: 2; URL=/tour/views/guide/guideDestinations.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Destination</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h1>Create a New Destination</h1>
    <form action="addDestination.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Destination Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>
        </div>
        
        <div class="form-group">
            <label for="image">Image:</label>
            <input type="text" id="image" name="image" >
        </div>
        <!-- TODO IMAGE MAY BE CHANGED -->
        <!-- <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*" >
        </div> -->
        <div class="form-group">
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="beach">Beach</option>
                <option value="mountain">Mountain</option>
                <option value="historical">Historical Site</option>
                <option value="city">City</option>
                <option value="adventure">Adventure</option>
                <option value="nature">Nature</option>
            </select>
        </div>
        <div class="form-group">
            <label for="best_time">Best Time to Visit:</label>
            <input type="text" id="best_time" name="best_time" required>
        </div>
        <div class="form-group">
            <label for="accessibility">Accessibility:</label>
            <select id="accessibility" name="accessibility" required>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
        </div>
        <div class="form-group">
            <input name="submit" value="Create" type="submit" />
        </div>
    </form>
</body>
</html>