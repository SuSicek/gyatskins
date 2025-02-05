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

// Check if the user is an admin
$sql = "SELECT is_admin FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if ($user['is_admin'] == 1) {
        header("Location: admin.php");
        exit();
    }
}

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
    <title>Your Skins</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="dashboard">
        <h2>Your CSGO Skins</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Rarity</th>
                <th>Price</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['rarity']); ?></td>
                        <td>$<?php echo htmlspecialchars($row['price']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>"></td>
                        <td>
                            <form method="post" action="delete_skin.php" style="display:inline;">
                                <input type="hidden" name="skin_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <button type="submit" name="delete_skin">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No skins available.</td></tr>
            <?php endif; ?>
        </table>

        <h2>Add a New Skin</h2>
        <form id="addSkinForm" method="post" action="add_skin.php">
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="rarity" placeholder="Rarity" required>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <input type="text" name="image_url" placeholder="Image URL" required>
            <button type="submit">Add Skin</button>
        </form>

        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
