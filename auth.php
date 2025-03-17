<?php
session_start();
require_once 'config.php'; // Fixed file name

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Use prepared statement to prevent SQL injection
    $checkEmailStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->get_result();
    
    if ($checkEmailResult->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered';
        $_SESSION['active_form'] = 'register'; // Fixed typo
    } else {
        // Use prepared statement for insert
        $insertStmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $insertStmt->bind_param("ssss", $name, $email, $password, $role);
        $insertStmt->execute();
        
        // Set success message
        $_SESSION['register_success'] = 'Registration successful! Please log in.';
    }

    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Use prepared statement for login
    $loginStmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $loginStmt->bind_param("s", $email);
    $loginStmt->execute();
    $result = $loginStmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; // Store user ID for security
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role']; // Store role for access control

            if ($user['role'] === 'admin') {
                header("Location: admin_page.php"); // Added semicolon
            } else {
                header("Location: user_page.php"); // Added semicolon
            }
            exit();
        }
    }

    $_SESSION['login_error'] = 'Incorrect Email or Password'; 
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}
?>