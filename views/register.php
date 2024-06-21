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

    //TODO CHECK IF THE ACCOUNT ALREADY EXISTST
     
    $stmt = $conn->prepare("SELECT id,email,username FROM users WHERE email = ? UNION SELECT id,email,username FROM guides WHERE email =?");    
    $stmt->bind_param("ss", $email,$email);

    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
      
    
    
    //TODO CHECK FOR THE USER ACCOUNT
    
    if($user){
      
      //TODO DISPLAY INFORMATION THAT THE user already exists
      
      $message['error'] = 'An account already exists with the credentials you entered.';
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
    <link rel="stylesheet" href="/tour/public/css/create-account.css" />
    <link rel="stylesheet" href="/tour/public/css/general.css" />
    <title>Create Account</title>
  </head>
  <body>
    <div class="login__container">
      <h2>Create <span>Tour App</span> account</h2>
      <form id="loginForm" action="register.php" method="POST">
      <div class="login__container-group">
          <div class="input-group-illustration">
            <object type="image/svg+xml" data="/tour/assets/img/tour_guides.svg">
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
              <label for="username">Email</label>
              <input
                type="text"
                id="email"
                name="email"
                placeholder="example: abc@example.com"
                required
              />
            </div>
            <div class="input-group">
              <label>Create Account As</label>
              <label style="display: inline-block" for="role">User</label>
              <input
                type="radio"
                id="role_user"
                name="role"
                value="user"
                required
                checked
              />
              <label style="display: inline-block" for="role">Tour Guide</label>
              <input
                type="radio"
                id="role_tg"
                name="role"
                value="tour_guide"
                required
              />
            </div>
            <div class="input-group input-group-role"></div>
            <!-- <div class="input-group">
              <label for="username">Phone Number</label>
              <input
                type="phone"
                id="phone"
                name="phone"
                placeholder="example: 0912345678"
                required
              />
            </div> -->
            
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
            <div class="input-group input-button">
              <span class="error-message-inline"></span>
              <button type="submit">Create Account</button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <footer>copyright &copy; 2023 Web Assignment</footer>
    <script>
      const appendedTO = document.querySelector(".input-group-role");
      const form = document.getElementById("loginForm");
      const send = document.querySelector('button[type="submit"]');
      const name = document.getElementById("username");
      const email = document.getElementById("email");
      const password = document.getElementById("password");
      const confPassword = document.getElementById("confirm_password");

      let selectedOption = "";
      document.querySelectorAll('input[name="role"]').forEach((radio) => {
        radio.addEventListener("change", () => {
          selectedOption = document.querySelector(
            'input[name="role"]:checked'
          ).value;
          if (selectedOption === "tour_guide") {
            appendedTO.innerHTML = `<label for="phone">Phone Number</label>
            <input
            type="phone"
            id="phone"
            name="phone"
            placeholder="example: 0912345678"
            value=""
            required
            />`;
          } else {
            appendedTO.innerHTML = "";
          }
        });
      });

      function validateForm() {
        const err = document.querySelector(".error-message-inline");
        err.textContent = "";
        let isValid = true;

        if (name.value.trim() === "" || !isNaN(name.value.trim().charAt(0))) {
          err.textContent = "invalid username";
          isValid = false;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value.trim())) {
          err.textContent = "invalid email";
          isValid = false;
        }

        if (selectedOption === "tour_guide") {
          const phoneRegex = /^(?:\+2519\d{8}|09\d{8})$/;
          if (!phoneRegex.test(phone.value.trim())) {
            err.textContent = "invalid phone number";
            isValid = false;
          }
        }

        let passwd = password.value.trim();
        // console.log("password: ", passwd);
        if (passwd === "" || passwd.length < 8) {
          err.textContent =
            "password should not be empty and atleast 8 characters long";
          isValid = false;
        }

        // console.log("password: ", confPassword.value.trim());
        if (passwd !== confPassword.value.trim()) {
          // console.log("passwd dont match");
          err.textContent = "passwords do not match";
          isValid = false;
        }

        return isValid;
      }

      form.addEventListener("submit", function (e) {
        if (!validateForm()) {
          // console.log("prevented");
          e.preventDefault();
        } else {
          // console.log("redirecting");
          // window.location.href = "index.html";
        }
      });
    </script>
  </body>
</html>