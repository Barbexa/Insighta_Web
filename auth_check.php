<?php
session_start();


// Check if the cookie exists
$token = $_COOKIE['auth_token'] ?? null;

if (!$token) {
    header("Location: login.php");
    exit;
}

// Verify the token exists in the 'tokens' table and hasn't expired
$stmt = $conn->prepare("
    SELECT u.* FROM users u 
    JOIN tokens t ON u.id = t.user_id 
    WHERE t.token_value = ? 
    AND t.expires_at > CURRENT_TIMESTAMP
");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    // Token invalid or expired
    setcookie("auth_token", "", time() - 3600, "/"); // Clear the bad cookie
    header("Location: login.php?error=session_expired");
    exit;
}

// Success! User is authenticated.
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_role'] = $user['role'];