<?php
include "config.php";

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $db";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db($db);

// Create news table
$table_sql = "CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    category VARCHAR(50) NOT NULL,
    image VARCHAR(255),
    breaking TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($table_sql) === TRUE) {
    echo "News table created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Insert sample data
$sample_data = [
    [
        'title' => 'Welcome to GLOBAL ARTICLES',
        'content' => 'This is your first news article. Stay tuned for more updates!',
        'category' => 'general',
        'breaking' => 1
    ],
    [
        'title' => 'Technology Advancements in 2024',
        'content' => 'The latest in AI and machine learning technologies are revolutionizing industries worldwide.',
        'category' => 'technology',
        'breaking' => 0
    ],
    [
        'title' => 'Sports Championship Results',
        'content' => 'Exciting matches and championship winners announced today.',
        'category' => 'sports',
        'breaking' => 0
    ]
];

foreach ($sample_data as $data) {
    $stmt = $conn->prepare("INSERT INTO news (title, content, category, breaking) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $data['title'], $data['content'], $data['category'], $data['breaking']);
    if ($stmt->execute()) {
        echo "Sample news inserted: " . $data['title'] . "<br>";
    }
    $stmt->close();
}

echo "<br><strong>Setup complete!</strong> You can now visit your website at http://localhost/GLOBALARTICLE/";

$conn->close();
?></content>
<parameter name="filePath">c:\xampp\htdocs\GLOBALARTICLE\setup-db.php