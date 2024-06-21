<?php
session_start();
require_once'../util/functions.php';

$servername = "localhost";
$username = "teme";
$password = "12345678";
$dbname = "tour";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if($_SERVER['REQUEST_METHOD']==='GET'){
    
    if (!isset($_GET['id'])) {
        die("No destination ID provided as get.");
    }
    else {
        $destination_id = intval($_GET['id']);
        $_SESSION['dest_id'] = $destination_id;
    }
} 
else {
    
    if (!isset($_POST['id'])) {
        die("No destination ID provided as post.");
    }
    else {
        $destination_id = intval($_POST['id']);
        $_SESSION['dest_id'] = $destination_id;
    }
}


$stmt = $conn->prepare("SELECT name, description, location, image_url,category, best_season, accessibility  FROM destinations WHERE id = ?");
$stmt->bind_param('i', $destination_id);
$stmt->execute();
$result = $stmt->get_result();
$resultRowsCount = $result->num_rows;

if( $resultRowsCount > 0) 
    $destination = $result->fetch_assoc();

$stmt->close();

//handle comments
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['comment'], $_POST['rating']) && isset($_SESSION['role']) && $_SESSION['role'] !== 'tour_guide') {
    
    $comment = $_POST['comment'];
    $rating = intval($_POST['rating']);
    $user_id = $_SESSION['id'];
    $username = $_SESSION['username'];

    
    if (!empty($comment) && $rating > 0 && $rating <= 5) {
        $stmt = $conn->prepare("INSERT INTO destination_reviews (user_id, destination_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('iiis', $user_id, $destination_id, $rating, $comment);

        if ($stmt->execute()) {
            $success_message = "Your review has been submitted successfully.";
            
            // Fetch the newly inserted comment to display
            $new_comment = [
                'username' => $username,
                'rating' => $rating,
                'comment' => $comment
            ];
        } else {
            $error_message = "Error submitting your review. Please try again.";
        }

        $stmt->close();
    } else {
        $error_message = "Please provide a valid rating and comment.";
    }
}

// Fetch existing comments
$stmt = $conn->prepare("SELECT u.username, r.rating, r.comment FROM destination_reviews r JOIN users u ON r.user_id = u.id WHERE r.destination_id = ?");
$stmt->bind_param('i', $destination_id);
$stmt->execute();
$result = $stmt->get_result();
$comments = [];

//review is an assoc array
//so comments is multidimensional array
while ($review = $result->fetch_assoc()) {
    $comments[] = $review;
}
//append 
if (isset($new_comment)) {
    $comments[] = $new_comment; // Append the new comment to the array
}

// dumpAndDie($comments);

$stmt->close();
$conn->close();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($destination['name'], ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="icon" type="image/png" href="/tour/assets/img/web-logo.png" />
    <link rel="stylesheet" href="/tour/public/css/places.css" />
    <link rel="stylesheet" href="/tour/public/css/general.css">
</head>
<body>
<header>
    <nav>
        <p><span>Tour</span> <strong>App</strong></p>
        <ul class="list__navigations">
            <li><a href="/tour/views/guide/guideDestinations.php">Destinations</a></li>
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
        </button>
    </nav>
</header>

<main>
      <div class="search__container">
        <div class="search__container-inputs">
          <input
            type="text"
            class="search search__places"
            placeholder="Search for places"
          />
          <input
            type="text"
            class="search search__events"
            placeholder="Search for events"
          />
        </div>
        <button class="search-button">
          <object type="image/svg+xml" data="/tour/assets/img/search.svg">
            Your browser does not support SVG.
          </object>
        </button>
        
        <!-- to display search not found -->
        <?php if(!$resultRowsCount) {?>
            <div id="error-message" class="error-message">
                No places found
            </div>
            <?php die(); }?>
      </div>

<section>
    <div class="destination-detail">
        
    <!-- DESTINATION IMAGEES-->
        <div class="header-delete-add">
            <h1><?php echo htmlspecialchars($destination['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
        </div>

        <section class="gallery">
            <div class="gallery__item">
                <?php if ($destination['image_url']) { ?>
                    <img 
                        class="gallery__img gallery__img--first" 
                        src="<?php echo htmlspecialchars($destination['image_url'], ENT_QUOTES, 'UTF-8'); ?>" 
                        alt="Image of <?php echo htmlspecialchars($destination['name'], ENT_QUOTES, 'UTF-8'); ?>"
                    />
                <?php } ?>
            </div>
        </section>

        <!--DESTINATION INFO-->
        <section class="details">
        <div class="deatils__rate-para">
          <h2>About This Place</h2>
          <div class="details__rating">
            <span class="details__rating--stars">[rating]</span>
            <span class="details__rating--number-of-people">[ex: 1000]</span>
            reviews
          </div>
          
          <p><strong>Location:</strong> <?php echo htmlspecialchars($destination['location'], ENT_QUOTES, 'UTF-8'); ?></p>
          <p><strong>Best Season:</strong> <?php echo htmlspecialchars($destination['best_season'], ENT_QUOTES, 'UTF-8'); ?></p>
          <p>
            <?php echo htmlspecialchars($destination['description'], ENT_QUOTES, 'UTF-8'); ?>
          </p>
        
          <div class="details__rating--bookmarks">
            <button id="details__rating--icon-id" class="details__rating--icon">
              <img
                src="/tour/assets/img/icons/bookmark_3916600.svg"
                alt="bookmark icon"
                class="bookmark-icon"
              />
              <!-- <button class="nav__btn nav__btn--bookmarks"> -->
              <!-- <svg class="nav__icon">
                <use href="/tour/assets/img/icons/icons.svg#icon-bookmark"></use>
              </svg> -->
              <!-- </button> -->
            </button>
            <span class="bookmark-this-place">&larr; Bookmark This place </span>
          </div>
    </div>

    <!--Tour guide Info -->
    <div class="guide-section">
          <h3>This place was created by</h3>
          <div id="guides-container " class="grid__container">
            <div class="guide">
              <div class="profile-picture">
                <img
                  src="/tour/assets/img/img-europe/italy/alex-vasey-F5HtPjpBb9k-unsplash.jpg"
                  alt="Guide Picture"
                />
              </div>
              <span class="guide-name">John Doe</span>
              <div class="guide-header">
                <span class="rating">★★★★☆</span>
              </div>
            </div>
            <div class="guide-details">
              <p>contact</p>
              <div class="guide-addresses">
                <div class="guide-address">
                  <img
                    src="/tour/assets/img/icons/phone-rotary_11747112.svg"
                    alt=""
                  />
                  <a href="tel:+1234567890">Call 123-456-7890</a>
                </div>
                <div class="guide-address">
                  <img src="/tour/assets/img/icons/envelope_3916632.svg" alt="" />
                  <a href="mailto:someone@example.com">someone@example.com</a>
                </div>
              </div>
            </div>
            <div class="comment-form-para-rating rating-box">
              <p>Rate the Tour Guide</p>
              <ul class="rate-area">
                  <hr />
                  <input
                    type="radio"
                    id="5-star"
                    name="grating"
                    class="guide-ratings"
                    value="5"
                  /><label for="5-star" title="Amazing">5</label>
                  <input
                    type="radio"
                    id="4-star"
                    name="grating"
                    class="guide-ratings"
                    value="4"
                  /><label for="4-star" title="Good">4</label>
                  <input
                    type="radio"
                    id="3-star"
                    name="grating"
                    class="guide-ratings"
                    value="3"
                  /><label for="3-star" title="Average">3</label>
                  <input
                    type="radio"
                    id="2-star"
                    name="grating"
                    class="guide-ratings"
                    value="2"
                  /><label for="2-star" title="Not That Bad">2</label>
                  <input
                    type="radio"
                    id="1-star"
                    name="grating"
                    class="guide-ratings"
                    value="1"
                  /><label for="1-star" title="Bad">1</label>
                </ul>

              </div>
            </div>
          </div>
        </div>
    </section>
 
    <!-- comment section -->
 <div class="comment-section">
        <h3>Comments</h3>
        <div id="comments-container">
          <div class="comment">
            
            <!--COMMENT PIC-->
            <div class="profile-picture">
              <img
                src="/tour/assets/img/img-europe/greece/patrick-EvMearrxas4-unsplash.jpg"
                alt="Profile Picture"
              />
            </div>
            
            <!--COMMENT DETAILS-->
            <div class="comment-details">
              <div class="comment-header">
                <span class="username">Temesgen</span>
                <span class="rating">★★★★☆</span>
              </div>
              <p class="comment-header--comment">
                This place is amazing! Had a wonderful time visiting.
              </p>
            </div>
          </div>
          
          <!-- <div class="comment">
            <div class="profile-picture">
              <img
                src="/tour/assets/img/img-africa/ethiopia/michal-nevaril-w6KpyR5SY80-unsplash.jpg"
                alt="Profile Picture"
              />
            </div>
            <div class="comment-details">
              <div class="comment-header">
                <span class="username">Noh</span>
                <span class="rating">★★★☆☆</span>
              </div>
              <p class="comment-header--comment">
                It was an okay experience. The service could be better.
              </p>
            </div>
          </div>
          <div class="comment">
            <div class="profile-picture">
              <img
                src="/tour/assets/img/img-africa/ethiopia/michal-nevaril-w6KpyR5SY80-unsplash.jpg"
                alt="Profile Picture"
              />
            </div>
            <div class="comment-details">
              <div class="comment-header">
                <span class="username">Tsinat</span>
                <span class="rating">★★★☆☆</span>
              </div>
              <p class="comment-header--comment">great experience</p>
            </div>
          </div> -->

        </div>


        <div class="comment-form">

        <form action ="destination-detail.php" method="POST" >
          <div class="form-details">
            <div class="comment-form-para-rating rating-box">
              <p>Leave a comment</p>
              <div class="rating-stars stars">
              <ul class="rate-area">
                  <hr />
                  <input
                    type="radio"
                    id="5-star"
                    name="rating"
                    class="place-ratings"
                    value="5"
                  /><label for="5-star" title="Amazing">5</label>
                  <input
                    type="radio"
                    id="4-star"
                    name="rating"
                    class="place-ratings"
                    value="4"
                  /><label for="4-star" title="Good">4</label>
                  <input
                    type="radio"
                    id="3-star"
                    name="rating"
                    class="place-ratings"
                    value="3"
                  /><label for="3-star" title="Average">3</label>
                  <input
                    type="radio"
                    id="2-star"
                    name="rating"
                    class="place-ratings"
                    value="2"
                  /><label for="2-star" title="Not That Bad">2</label>
                  <input
                    type="radio"
                    id="1-star"
                    name="rating"
                    class="place-ratings"
                    value="1"
                  /><label for="1-star" title="Bad">1</label>
                </ul>
              </div>
            </div>
            <textarea
            id="comment-text"
            placeholder="Write your comment"
            name="comment"
            ></textarea>
             
            <input type="hidden" name="id" value="<?php echo $_SESSION['dest_id']; ?>"  />
            <button type="submit" name="submit" id="submit-comment"> Submit</button>
        </form>

        </div>
        </div>
      </div>
    </main>
    <script src="/tour/public/js/bookmark.js" defer></script>
    <script>
        const gratings = document.querySelectorAll('.guide-ratings');
        const pratings = document.querySelectorAll('.place-ratings');
        let guide_rating = 0;
        let place_rating = 0;

        gratings.forEach(grating => {
            grating.addEventListener('click', () => {
                guide_rating = grating.value;
                console.log(guide_rating);
            })
            
        });

        pratings.forEach(rating => {
            rating.addEventListener('click', () => {
                place_rating = rating.value;
                console.log(place_rating);
            })
            
        });
    </script>
</body>
</html>
