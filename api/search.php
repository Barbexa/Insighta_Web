<?php

include "header.php";

$search = $_GET['q'] ?? '';
$results = [];

if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM profiles WHERE name LIKE ? LIMIT 10");
    $stmt->execute(["%$search%"]);
    $results = $stmt->fetchAll();
}
?>

<h2 class="text-2xl font-bold mb-6">Search Profiles</h2>
<form method="GET" class="mb-8 flex gap-4">
    <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Enter name..."
        class="flex-1 p-3 border rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 outline-none">
    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold">Search</button>
</form>

<div class="grid gap-4">
    <?php foreach ($results as $res): ?>
        <div class="bg-white p-4 rounded-lg border shadow-sm flex justify-between items-center">
            <div>
                <p class="font-bold capitalize"><?= $res['name'] ?></p>
                <p class="text-sm text-gray-500"><?= $res['gender'] ?> • <?= $res['country_id'] ?></p>
            </div>
            <a href="profile-detail.php?id=<?= $res['id'] ?>" class="text-blue-600 font-medium">View Detail →</a>
        </div>
    <?php endforeach; ?>
</div>