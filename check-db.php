<?php
include "config.php";

$result = $conn->query("SELECT id, title, category, breaking, created_at FROM news ORDER BY created_at DESC");

echo "Current news in database:\n";
echo "========================\n";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        echo "ID: " . $row['id'] . "\n";
        echo "Title: " . $row['title'] . "\n";
        echo "Category: " . $row['category'] . "\n";
        echo "Breaking: " . $row['breaking'] . "\n";
        echo "Date: " . $row['created_at'] . "\n";
        echo "---\n";
    }
} else {
    echo "No news found in database!\n";
}

$conn->close();
?>