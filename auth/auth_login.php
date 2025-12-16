<?php
// /auth/auth_login.php
session_start();
include("../app/config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Sanitize Inputs
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // 2. Check Database
    $sql = "SELECT user_id, name, role, password FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        // 3. Verify Password
        // Note: Since we are storing plain text passwords for now, we compare directly.
        // If you used password_hash() in api_create.php, use password_verify($password, $row['password']) here.
        if ($password === $row['password']) {
            
            // Success! Store user info in session
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];
            
            // Redirect to the dashboard/user page
            header("Location: ../dashboard.php");
            exit();
        } else {
            // Wrong Password
            header("Location: login.php?error=Invalid Password");
            exit();
        }
    } else {
        // User not found
        header("Location: login.php?error=User not found");
        exit();
    }
}
?>