<?php
session_start();

// require_once '../util/functions.php';

if (isset($_SESSION['role'])) {
  header('Location: /views/dashboard.php');
  exit();
}

//todo display the error in the html template
$error = "";

// LOGIN THE USER IF THE CREDENTIALS MATCH
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = new mysqli('localhost', 'teme', '12345678', 'tour');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //CHECK IF THE USER IS IN THE USERS TABLE 
    $stmt = $conn->prepare("SELECT id,email,username,password FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    //label as user if found in users table
    if($user)
      $role = 'user';  
    else {
      //ELSE IF USER NOT IN USER check if in guides 
        $stmt = $conn->prepare("SELECT id,username,email,password FROM guides WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        // Change role if found in guides table
        if($user)
          $role = 'tour_guide';  
    }

    if ($user && password_verify($password, $user['password'])) {
        
        $_SESSION['id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $role;
        header("Location: /tour/views/index.php");
        exit();

    } else {
        $error = "Invalid email or password.";
    }

    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login to your Tour App account</title>
    <link rel="stylesheet" href="./assets/css/login.css" />
    <script src="scripts.js" defer></script>
  </head>
  <body>
    <div class="login__container">
      <h2>Sign in to <span>Tour App</span></h2>
      <form id="loginForm" action="login.php" method="post">
        <div id="error-message"></div>
        <div class="input-group">
          <label for="email">Email</label>
          <input
            type="text"
            id="email"
            name="email"
            placeholder="example: John"
            required
          />
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="enter password"
          />
        </div>
        <p>
          <?php echo $error; ?>
        </p>
        <p>
          Don't have an account?
          <a
            href="create-account.html
            "
            >Create one here</a
          >
        </p>
        <div class="input-group input-button">
          <button type="submit">Login</button>
        </div>
        <object type="image/svg+xml" data="./assets/img/login-page.svg">
          Your browser does not support SVG.
        </object>
      </form>
    </div>
    <footer>copyright &copy; 2023 Web Assignment</footer>
  </body>
</html>
