<?php
session_start();

// Check if the user is logged in and is a tour guide
//TODO SESSION NOT BEING SET 
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'tour_guide') {
    header('Location: /views/login.php');
    exit();
}

$conn = new mysqli('localhost', 'teme', '12345678', 'tour');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the destination id from the query string
$destination_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($destination_id <= 0) {
    die("Invalid destination ID.");
}

// Fetch the existing destination details
$stmt = $conn->prepare("SELECT name, description, location, image_url, category, best_season, accessibility FROM destinations WHERE id = ?");
$stmt->bind_param('i', $destination_id);
$stmt->execute();
$result = $stmt->get_result();
$destination = $result->fetch_assoc();
$stmt->close();

if (!$destination) {
    die("Destination not found.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form inputs
    $name = $_POST['name'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $best_season = $_POST['best_time'];
    $accessibility = $_POST['accessibility'];
    $imagePath = $_POST['image'];
    
    //TODO FIX FILE UPLAODING
    /* if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $image = $_FILES['image'];
        $imagePath = 'uploads/' . basename($image['name']);
        if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            die("Error uploading image.");
        }
    } else {
        $imagePath = $destination['image'];
    } */

    $stmt = $conn->prepare("UPDATE destinations SET name=?, description=?, location=?, image_url=?, category=?, best_season=?, accessibility=? WHERE id=?");
    $stmt->bind_param('sssssssi', $name, $description, $location, $imagePath, $category, $best_season, $accessibility, $destination_id);

    if ($stmt->execute()) {
        // echo "Destination updated successfully!";
        header("Location:/tour/views/guide/guideDestinations");
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
    <title>Edit Destination</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h1>Edit Destination</h1>
    <form action="updateDestination.php?id=<?php echo $destination_id; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Destination Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($destination['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($destination['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($destination['location']); ?>" required>
        </div>

        <div class="form-group">
            <label for="image">Image:</label>
            <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($destination['location']); ?>" required>
        </div>

        
        <!--TODO IMPLEMENT IMAGES CORRECTLY -->
        <!-- <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*">
            <!-- <?php if (!empty($destination['image'])): ?> -->
                <!-- <img src="<?php echo htmlspecialchars($destination['image']); ?>" alt="Current Image" width="100"> -->
            <!-- <?php endif; ?> -->
        <!-- </div> -->

        <div class="form-group">
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="beach" <?php echo ($destination['category'] === 'beach') ? 'selected' : ''; ?>>Beach</option>
                <option value="mountain" <?php echo ($destination['category'] === 'mountain') ? 'selected' : ''; ?>>Mountain</option>
                <option value="historical" <?php echo ($destination['category'] === 'historical') ? 'selected' : ''; ?>>Historical Site</option>
                <option value="city" <?php echo ($destination['category'] === 'city') ? 'selected' : ''; ?>>City</option>
                <option value="adventure" <?php echo ($destination['category'] === 'adventure') ? 'selected' : ''; ?>>Adventure</option>
                <option value="nature" <?php echo ($destination['category'] === 'nature') ? 'selected' : ''; ?>>Nature</option>
            </select>
        </div>
        <div class="form-group">
            <label for="best_time">Best Time to Visit:</label>
            <input type="text" id="best_time" name="best_time" value="<?php echo htmlspecialchars($destination['best_season']); ?>" required>
        </div>
    
        <div class="form-group">
            <label for="accessibility">Accessibility:</label>
            <select id="accessibility" name="accessibility" required>
                <option value="yes" <?php echo ($destination['accessibility'] === 'yes') ? 'selected' : ''; ?>>Yes</option>
                <option value="no" <?php echo ($destination['accessibility'] === 'no') ? 'selected' : ''; ?>>No</option>
            </select>
        </div>
        <div class="form-group">
            <input name="submit" value="Update" type="submit" />
        </div>
    </form>
</body>
</html>