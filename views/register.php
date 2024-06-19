<?php
require '../util/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $role = $_POST['role']; 

    //TODO VALIDATE DONE ON THE FRONT


    
    $conn = new mysqli('localhost', 'teme', '12345678', 'tour');
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //TODO CHECK IF THE ACCOUNT ALREADY EXISTS
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");    
    $stmt->bind_param("s", $email);

    $stmt->execute();
    $user = $stmt->fetch();
    
    
    if($user){
        //user already exists
        header("location: /tour/views/login.php");
        exit();

    } else {

        //TODO new user; add to the database and then log the user in and redirect

        $stmt = $conn->prepare("INSERT INTO users (username,email,phoneno ,password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username,$email,$phone, $password, $role);
    
        if ($stmt->execute()) {
            echo "Registration successful!";
            
            //TODO mark that the user has logged in
            $_SESSION['user'] = [
                'email' => $email,
                ///may be add 
            ];

        } else {
            echo "Error: " . $stmt->error;
        }
    
        $stmt->close();
        $conn->close();
    }

    header("location: /tour/index.php");
    exit();

    
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./assets/css/create-account.css" />
    <title>Create Account</title>
  </head>
  <body>
    <div class="login__container">
      <h2>Create <span>Tour App</span> account</h2>
      <form id="loginForm" action="register.php" method="POST">
        <div id="error-message"></div>
        <div class="login__container-group">
          <div class="input-group-illustration">
            <object type="image/svg+xml" data="./assets/img/tour_guides.svg">
              Your browser does not support SVG.
            </object>
          </div>
          <div>
            <div class="input-group">
              <label for="username">Username</label>
              <input
                type="text"
                id="username"
                name="username"
                placeholder="example: John"
                required
              />
            </div>
            <div class="input-group">
              <label for="email">Email</label>
              <input
                type="email"
                id="email"
                name="email"
                placeholder="example: abc@example.com"
                required
              />
            </div>
            <div class="input-group">
              <label for="phone">Phone Number</label>
              <input
                type="phone"
                id="phone"
                name="phone"
                placeholder="example: 0912345678"
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
            <div class="input-group">
              <label for="password">Confirm Password</label>
              <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                placeholder="type the password again"
              />
            </div>
            <div class="input-group">
                <label for ="role">Role</label> 
                <select id="role" name="role" required>
                    <option value="user">User</option>
                    <option value="tour_agent">Tour Agent</option>
                </select><br>
            </div>
            <div class="input-group input-button">
              <button type="submit">Create Account</button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <footer>copyright &copy; 2023 Web Assignment</footer>
  </body>
</html>
