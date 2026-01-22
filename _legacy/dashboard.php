<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}
include "config.php";

// Count total news
$result = $conn->query("SELECT COUNT(*) AS total FROM news");
$totalNews = $result->fetch_assoc()['total'];

// Count breaking news
$result2 = $conn->query("SELECT COUNT(*) AS breaking FROM news WHERE breaking=1");
$breakingNews = $result2->fetch_assoc()['breaking'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard | GLOBAL ARTICLES</title>
  <style>
    body{font-family:Arial;padding:20px;}
    a{color:#0b3c74;text-decoration:none;margin-right:15px;}
    .stats{display:flex;gap:20px;margin-top:20px;}
    .card{padding:20px;background:#f1f1f1;border-radius:6px;width:200px;text-align:center;}
  </style>
</head>
<body>
  <h1>Admin Dashboard</h1>
  <a href="add-news.php">Add News</a>
  <a href="manage-news.php">Manage News</a>
  <a href="logout.php">Logout</a>

  <div class="stats">
    <div class="card">
      <h3>Total News</h3>
      <p><?php echo $totalNews; ?></p>
    </div>
    <div class="card">
      <h3>Breaking News</h3>
      <p><?php echo $breakingNews; ?></p>
    </div>
  </div>
  
</body>
</html>
