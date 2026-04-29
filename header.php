<?php
session_start();
// Redirect to login if no session or cookie (Requirement: HTTP-only cookie check)
if (!isset($_SESSION['user_id']) || !isset($_COOKIE['auth_token'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Insighta | Admin Portal</title>
</head>

<body class="bg-gray-50 flex min-h-screen">
    <aside class="w-64 bg-slate-900 text-white flex flex-col sticky top-0 h-screen">
        <div class="p-6 text-2xl font-bold border-b border-slate-800">Insighta</div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="dashboard.php" class="block p-3 rounded hover:bg-slate-800">📊 Dashboard</a>
            <a href="profiles.php" class="block p-3 rounded hover:bg-slate-800">👥 Profiles</a>
            <a href="search.php" class="block p-3 rounded hover:bg-slate-800">🔍 Search</a>
            <a href="account.php" class="block p-3 rounded hover:bg-slate-800">⚙️ Account</a>
        </nav>
        <div class="p-4 border-t border-slate-800">
            <a href="logout.php" class="text-sm text-slate-400 hover:text-white">Logout</a>
        </div>
    </aside>
    <main class="flex-1 p-8"></main>
    </main>