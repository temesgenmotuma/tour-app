<?php
    require_once base_path('/util/Database.php'); 
    
    dumpAndDie($_SERVER);

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $db = new Database('localhost', 'teme', '12345678', 'tour');
    
    $db->connect();
    
    $conn = $db->connection;

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    render('register.php');
