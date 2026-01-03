<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}
include "config.php";

// Delete news
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM news WHERE id=$id");
}

$result = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage News | GLOBAL ARTICLES</title>
  <style>
    body{font-family:Arial;padding:20px;}
    table{width:100%;border-collapse:collapse;margin-top:20px;}
    th, td{border:1px solid #ccc;padding:10px;text-align:left;}
    a{color:#0b3c74;text-decoration:none;margin-right:10px;}
  </style>
</head>
<body>
  <h1>Manage News</h1>
  <a href="dashboard.php">Back to Dashboard</a>
  <table>
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Category</th>
      <th>Breaking</th>
      <th>Actions</th>
    </tr>
    <?php while($row = $result->fetch_assoc()){ ?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td><?php echo $row['title']; ?></td>
      <td><?php echo $row['category']; ?></td>
      <td><?php echo $row['breaking'] ? 'Yes' : 'No'; ?></td>
      <td>
        <a href="add-news.php?edit=<?php echo $row['id']; ?>">Edit</a>
        <a href="manage-news.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this news?')">Delete</a>
      </td>
    </tr>
    <?php } ?>
  </table>
</body>
</html>
