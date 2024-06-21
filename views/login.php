<?php
session_start();

// require_once '../util/functions.php';

if (isset($_SESSION['role'])) {
  header('Location: /tour/views/index.php');
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
    <link rel="stylesheet" href="/tour/public/css/login.css" />
  </head>
  <body>
    <div class="login__container">
      <h2>Sign in to <span>Tour App</span></h2>
      <form id="loginForm" action="login.php" method="post">
          <div class="input-group">
            <label for="email">Email</label>
            <input
              type="text"
              id="email"
              name="email"
              placeholder="example: abc@example.com"
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
          <div class="error-message-inline"></div>
          <p class="error-message-inline">
            <?php echo $error; ?>
          </p>
          <p>
            Don't have an account?
            <a href="/tour/views/register">Create one here</a>
          </p>
          <div class="input-group input-button">
            <button type="submit" name="value">Login</button> 
          </div>
          <object type="image/svg+xml" data="/tour/assets/img/login-page.svg">
            Your browser does not support SVG.
          </object>
      </form>
    </div>
    <footer>copyright &copy; 2023 Web Assignment</footer>
    <script>
      const form = document.getElementById("loginForm");
      const send = document.querySelector('button[type="submit"]');
      const email = document.getElementById("email");
      const password = document.getElementById("password");

      // validate form
      function validateForm() {
        const err = document.querySelector(".error-message-inline");
        err.textContent = "";

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value.trim())) {
          console.log('whata guan', emailRegex.test(email.value.trim()));
          err.textContent = "Iinvalid Email or Password";
          return false;
        }

        //
        //TODO the pasword validation to be changed by teme after fetching the password
        //using the email from the database
        //

        let passwd = password.value.trim();
        if (passwd === "" || passwd.length < 8) {
          err.textContent =
            "password should not be empty and atleast 8 characters long";
          return false;
        }
        return true;
      }

      form.addEventListener("submit", function (e) {
        if (!validateForm()) {
          e.preventDefault();
        } 
      });
    </script>
  </body>
</html>
