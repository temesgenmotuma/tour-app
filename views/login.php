<?php
session_start();

// LOGIN THE USER IF THE CREDENTIALS MATCH
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    //todo display the error in the html template
    $error = "";

    $conn = new mysqli('localhost', 'teme', '12345678', 'tour');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, email, role FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            //Verifies that a password matches a hash
            //store the sessions
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            header('Location: index.php');
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with that Email.";
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
