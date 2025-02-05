<?php
session_start();

$servername = "localhost";
$username = "leteckyj";
$password = "cisco123";
$dbname = "leteckyj_";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $rarity = $_POST['rarity'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    // Check the length of the image_url
    if (strlen($image_url) > 512) {
        echo "Error: Image URL is too long.";
        exit();
    }

    $sql = "INSERT INTO skins (name, rarity, price, image_url, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdss", $name, $rarity, $price, $image_url, $user_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

$conn->close();
?>
