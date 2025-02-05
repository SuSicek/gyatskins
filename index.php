<?php
session_start();

// Ensure that the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['username']; // Get the logged-in username
$user_id = $_SESSION['user_id']; // Get the logged-in user ID

// Database connection settings
$servername = "localhost";
$username = "leteckyj";
$password = "cisco123"; // Your database password (leave blank if you're using default for local development)
$dbname = "leteckyj_";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = null;
$success = null;

// Handle skin addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_skin'])) {
    $name = $_POST['name'];
    $rarity = $_POST['rarity'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    // Prepare SQL query to insert the new skin
    $sql = "INSERT INTO skins (name, rarity, price, image_url, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdss", $name, $rarity, $price, $image_url, $user_id);

    if ($stmt->execute()) {
        $success = "Skin added successfully.";
    } else {
        $error = "Error: " . $stmt->error;
    }
}

// Fetch skins for the logged-in user from the database
$sql = "SELECT id, name, rarity, price, image_url FROM skins WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($user); ?>!</h1>

    <?php if ($user === 'admin'): ?>
        <p>You are logged in as an admin. You have full access.</p>
        <!-- Add admin-specific content -->
    <?php else: ?>
        <!-- Show the skins table for the logged-in user -->
        <h2>Your CSGO Skins</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Rarity</th>
                <th>Price</th>
                <th>Image</th>
            </tr>

            <?php
            // Display skins if available
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['rarity']) . "</td>
                            <td>$" . htmlspecialchars($row['price']) . "</td>
                            <td><img src='" . htmlspecialchars($row['image_url']) . "' alt='" . htmlspecialchars($row['name']) . "'></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No skins available.</td></tr>";
            }
            ?>

        </table>

        <!-- Add Skin Form -->
        <h2>Add a New Skin</h2>
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <input type="hidden" name="add_skin" value="1">
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="rarity" placeholder="Rarity" required>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <input type="text" name="image_url" placeholder="Image URL" required>
            <button type="submit">Add Skin</button>
        </form>
    <?php endif; ?>

    <p><a href="logout.php">Logout</a></p>

    <?php
    // Close database connection
    $conn->close();
    ?>
</body>
</html>
