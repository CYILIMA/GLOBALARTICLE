

<?php
session_start();
include "config.php";

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Simple hardcoded credentials (change later)
    if($username === "admin" && $password === "admin123"){
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login | GLOBAL ARTICLES</title>
  <style>
    body{font-family:Arial;background:#f5f5f5;display:flex;justify-content:center;align-items:center;height:100vh;}
    form{background:#fff;padding:30px;border-radius:6px;box-shadow:0 2px 8px rgba(0,0,0,0.2);}
    input{width:100%;padding:10px;margin:10px 0;border:1px solid #ccc;border-radius:4px;}
    button{padding:10px 20px;background:#0b3c74;color:#fff;border:none;border-radius:4px;cursor:pointer;}
    .error{color:red;margin-bottom:10px;}
  </style>
</head>
<body>
  <form method="post">
    <h2>Admin Login</h2>
    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button name="login">Login</button>
  </form>
</body>
</html>
