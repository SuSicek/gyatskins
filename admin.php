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

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT is_admin FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if ($user['is_admin'] != 1) {
        header("Location: dashboard.php");
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}

$error = null;
$success = null;

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    // Prepare SQL query to delete the user
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $success = "User deleted successfully.";
    } else {
        $error = "Error: " . $stmt->error;
    }
}

// Fetch the list of users
$sql = "SELECT id, username FROM users WHERE is_admin = 0";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="adminPanel">
        <h2>Admin Panel</h2>
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <!-- User Management Section -->
        <h3>User Management</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Skins</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td>
                                <?php
                                $user_id = $row['id'];
                                $sql = "SELECT name, rarity, price FROM skins WHERE user_id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $user_id);
                                $stmt->execute();
                                $skins_result = $stmt->get_result();
                                if ($skins_result->num_rows > 0): ?>
                                    <ul>
                                        <?php while ($skin = $skins_result->fetch_assoc()): ?>
                                            <li><?php echo htmlspecialchars($skin['name']) . " - " . htmlspecialchars($skin['rarity']) . " - $" . htmlspecialchars($skin['price']); ?></li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php else: ?>
                                    No skins available.
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="post" action="" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <button type="submit" name="delete_user" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
