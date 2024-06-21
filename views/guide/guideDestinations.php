<?php
session_start();

//TODO SESSION NOT BEING SET 
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'tour_guide') {
    header('Location: /tour/views/login');
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

$tour_guide_id = $_SESSION['id'];

$stmt = $conn->prepare("SELECT id, name, description, location, image_url FROM destinations WHERE tour_guide_id = ?");
$stmt->bind_param('i', $tour_guide_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destinations</title>
    <link rel="stylesheet" href="/tour/public/css/general.css" />
    <link rel="stylesheet" href="/tour/public/css/guide-destinations.css" />
</head>
<body>
    <h1>Explore Our Destinations</h1>
    <div class="grid-container">
        <?php if ($result->num_rows > 0) { ?>
            <!-- // Loop over each record and display its attributes -->
            <?php while($row = $result->fetch_assoc()) { ?>
                <div class="card-item">
                    
                    <?php if ($row["image_url"]) { ?>
                    
                    <div class="card__img">
                        <img class="grid-item__image" src="<?php echo htmlspecialchars($row["image_url"], ENT_QUOTES, 'UTF-8') ?>" alt="Image of <?php echo htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8') ?>" >
                    </div>
                        <!-- TODO Image updload  -->
                        <!-- <div class="card__img">
                            <img class="grid-item__image" src="' . htmlspecialchars($row["image_url"], ENT_QUOTES, 'UTF-8') . '" alt="Image of ' . htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8') . '">';
                        </div> -->
                    <?php } ?>
                    <div class="card__text">
                        <h2> <?php echo htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8') ?></h2>
                        <p> <?php echo htmlspecialchars($row["description"], ENT_QUOTES, 'UTF-8') ?></p>
                        <p> Location: <?php echo htmlspecialchars($row["location"], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>

                    <div class="btn">
                        <a href="/tour/views/guide/updateDestination?id=<?php echo $row['id'] ?>">Edit</a>
                    </div>

                    <!-- TODO DELETE Destinations -->
                    <form action="/tour/views/guide/deleteDestination" method="POST" >
                        <input type="hidden" name="id" value="<?php echo $row['id'];?>" />
                        <div class="btn btn-primary">
                            <input type="submit" class="inner-btn" name="Delete" value="Delete"  />
                        </div>
                    </form>
                </div>   
            <?php } ?>
        <?php } else { ?>
            <h2>No destinations found.</h2>
            <!-- TODO CREATE A LINK TO  -->
            <div class="btn btn-primary">
                <a href="/tour/views/guide/addDestination">Create now</a>
            </div>
        <?php } ?>
        <?php    
            $stmt->close();
            $conn->close();
        ?>
    </div>
</body>
</html>
