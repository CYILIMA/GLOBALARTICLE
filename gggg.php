<?php
// Check if form is submitted
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']); // Get name safely
    if (!empty($name)) {
        $message = "Hello, " . $name . "! Welcome to our website.";
    } else {
        $message = "Please enter your name.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple PHP Form</title>
</head>
<body>
    <h1>My PHP Webpage</h1>

    <!-- Display message -->
    <p><?php echo $message; ?></p>

    <!-- Form -->
    <form method="post" action="">
        <label for="name">Enter your name:</label>
        <input type="text" id="name" name="name">
        <input type="submit" value="Submit">
    </form>
</body>
</html>
