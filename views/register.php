<?php
session_start();
require '../util/functions.php';

if (isset($_SESSION['role'])) {
  header('Location: /tour/views/index.php');
  exit();
}

$message = ["success"=>"","error"=> ""];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $username = $_POST['username'];
    $role = $_POST['role']; 
    if($role ==='tour_guide')
      $phone = $_POST['phone'];

    //TODO VALIDATE DONE ON THE FRONT


    $conn = new mysqli('localhost', 'teme', '12345678', 'tour');
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //TODO CHECK IF THE ACCOUNT ALREADY EXISTS
    
      //TODO CHECK FOR THE GUIDE ACCOUNT

    if ($role ==="tour_guide"){
     
      $stmt = $conn->prepare("SELECT id,email,username FROM guides WHERE email = ?");    
      $stmt->bind_param("s", $email);
  
      $stmt->execute();
      $result = $statement->get_result();
      $user = $result->fetch_assoc();
      
    
    } 

    //TODO CHECK FOR THE USER ACCOUNT
    else if ($role === "user"){
      
      $stmt = $conn->prepare("SELECT id,email,username FROM users WHERE email = ?");    
      $stmt->bind_param("s", $email);
  
      $stmt->execute();
      $result = $statement->get_result();
      $user = $result->fetch_assoc();
    
    }
    
    //branch if user exists or doesn't for both user and guide
    if($user){

        //TODO DISPLAY INFORMATION THAT THE user already exists
        //user already exists

        $message['error'] = 'A user already exists with the credentials you entered.';
        /* header("Location: /tour/views/login");
        exit(); */

    } else {

        //TODO new user; add to the database and then log the user in and redirect
       
        if($role ==='tour_guide'){

          $stmt = $conn->prepare("INSERT INTO guides (username,email,phoneno ,password) VALUES (?, ?, ?, ?)");
          $stmt->bind_param("ssss", $username,$email,$phone, $password);
    
        } 
        else if($role ==='user'){

          $stmt = $conn->prepare("INSERT INTO users (username,email,password ) VALUES (?, ?, ?)");
          $stmt->bind_param("sss", $username,$email,$password);
      
        }

        if ($stmt->execute()) {
            $message['success'] = "Registration successful!";
            
            //mark that the user has logged in
            
            //TODO ROLE IS NOT BEING SET HERE FIX
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;

        } else {
            $message['error'] = "Error: " . $stmt->error;
        }
    
        $stmt->close();
        $conn->close();
        header("Location: /tour/views/index.php");
        exit();

    }


    
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
                    <option value="tour_guide">Tour Guide</option>
                </select><br>
            </div>
            <p>
              <?php echo $message['error']; ?>
            </p>
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
