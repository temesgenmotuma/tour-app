<?php
session_start();

$servername = "localhost";
$username = "teme";
$password = "12345678";
$dbname = "tour";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT id, name, description, location, image_url FROM destinations");
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destinations</title>
    <link rel="stylesheet" href="/tour/public/css/destinations.css">
    <link rel="stylesheet" href="/tour/public/css/general.css">
</head>
<body>
<header>
      <nav>
        <p><span>Tour</span> <strong>App</strong></p>
        <ul class="list__navigations">
          <li><a href="#">Destinations</a></li>
          <li><a href="#">Events</a></li>
          <li><a href="#">Bookmarks</a></li>
        </ul>
        <button class="btn__img--name">
          <span class="btn__img">
            <object
              type="image/svg+xml"
              data="/tour/assets/img/profile-circle-svgrepo-com.svg"
            >
              Your browser does not support SVG.
            </object>
          </span>
          <!-- <span>Sign in </span> -->
        </button>
      </nav>
    </header>
    <section>
    <h1>Explore Our Destinations</h1>
    <div class="grid__container">
        <?php if ($result->num_rows > 0) { ?>
            <!-- // Loop over each record and display its attributes -->
            <?php while($row = $result->fetch_assoc()) { ?>
                
                <a href="/tour/views/destination-detail?id=<?php echo $row['id']?>">
                <div class="grid-item">
                        <?php if ($row["image_url"]) { ?>
                        <img  class="grid-item__image" src="<?php echo htmlspecialchars($row["image_url"], ENT_QUOTES, 'UTF-8') ?>" alt="Image of <?php echo htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8') ?>" >
                        <!-- <img src="' . htmlspecialchars($row["image_url"], ENT_QUOTES, 'UTF-8') . '" alt="Image of ' . htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8') . '">'; -->
                        <div class="grid-item__details">
                            <h3> <?php echo htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p> Location: <?php echo htmlspecialchars($row["location"], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <?php } ?>
                    </div>
                </a>

            <?php } ?>
        <?php } else { ?>
            <p>No destinations found.</p>
        <?php } ?>
        <?php    
            $stmt->close();
            $conn->close();
        ?>
    </div>
    </section>
</body>
</html>
