<?php
include "config.php";

// Fetch all news
$result = $conn->query("SELECT * FROM news ORDER BY created_at DESC");

$newsData = [];
$breakingNews = [];

while($row = $result->fetch_assoc()){
    $item = [
        "id" => $row['id'],
        "title" => $row['title'],
        "content" => $row['content'],
        "category" => $row['category'],
        "image" => $row['image'] ? "uploads/" . $row['image'] : "",
        "breaking" => $row['breaking'],
        "created_at" => $row['created_at']
    ];

    $newsData[] = $item;

    if($row['breaking'] == 1){
        $breakingNews[] = $item;
    }
}

// Return JSON
header('Content-Type: application/json');
echo json_encode([
    "newsData" => $newsData,
    "breakingNews" => $breakingNews
]);
$conn->close();
?>
