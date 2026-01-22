<?php
session_start();
if(!isset($_SESSION['admin'])) header("Location: login.php");

include "config.php";

if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $breaking = isset($_POST['breaking']) ? 1 : 0;

    $imageName = $_FILES['image']['name'];
    $tmpName = $_FILES['image']['tmp_name'];
    $target = "uploads/" . basename($imageName);
    move_uploaded_file($tmpName, $target);

    $stmt = $conn->prepare("INSERT INTO news (title, content, category, image, breaking) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $title, $content, $category, $imageName, $breaking);
    $stmt->execute();
    $stmt->close();
    $success = "News added successfully!";
}
?>
