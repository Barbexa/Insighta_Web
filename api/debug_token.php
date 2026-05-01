<?php
// Include your header to get access to the $conn variable
include "header.php";

// Make sure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please log in to your portal first.");
}

// Fetch the most recent token for this user
$stmt = $conn->prepare("SELECT token_value FROM tokens WHERE user_id = ? AND token_type = 'access' ORDER BY expires_at DESC LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);
$token = $stmt->fetchColumn();

if ($token) {
    echo "<h3>Your Current Bearer Token:</h3>";
    echo "<code style='background: #eee; padding: 10px; border-radius: 5px;'>" . htmlspecialchars($token) . "</code>";
    echo "<p>Copy this string for your API testing tool (Thunder Client/Postman).</p>";
} else {
    echo "No active token found in database. Try logging out and logging back in.";
}
?>