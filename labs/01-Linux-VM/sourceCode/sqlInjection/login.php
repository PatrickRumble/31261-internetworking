<?php
session_start();

try {
    // Connect to SQLite database (file must exist in the same folder)
    $db = new PDO('sqlite:users.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['submit'])) {
        $username = $_POST['usr'] ?? '';
        $password = $_POST['psw'] ?? '';

        // NOTE: This is deliberately insecure (vulnerable to SQL Injection)
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $db->query($sql);

        if ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $_SESSION['username'] = $row['username'];
            echo "<h2>Welcome, " . htmlspecialchars($row['username']) . "!</h2>";
            echo '<a href="logout.php">Logout</a>';
        } else {
            echo "<p style='color:red;'>Invalid username or password.</p>";
        }
    }
} catch (PDOException $e) {
    echo "Database error: " . htmlspecialchars($e->getMessage());
}
?>
