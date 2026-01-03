<?php
include "config.php";

$category = $_GET['category'] ?? 'all';

if ($category === 'all') {
    $sql = "SELECT * FROM news ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "SELECT * FROM news WHERE category=? ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
}

$stmt->execute();
$result = $stmt->get_result();

$news = [];
while ($row = $result->fetch_assoc()) {
    $news[] = $row;
}

echo json_encode($news);
