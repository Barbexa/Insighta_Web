<?php
require "db.php";
include "header.php";

$page = (int) ($_GET['page'] ?? 1);
$limit = 10;
$offset = ($page - 1) * $limit;

// Filter logic
$gender = $_GET['gender'] ?? '';
$sql = "SELECT * FROM profiles WHERE 1=1";
if ($gender)
    $sql .= " AND gender = " . $conn->quote($gender);
$sql .= " ORDER BY processed_at DESC LIMIT $limit OFFSET $offset";

$profiles = $conn->query($sql)->fetchAll();
?>

<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Identified Profiles</h2>

        <form method="GET" class="flex gap-2">
            <select name="gender" class="border rounded px-3 py-1 text-sm bg-white">
                <option value="">All Genders</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
            <button type="submit" class="bg-slate-800 text-white px-4 py-1 rounded text-sm">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-4 text-left">Name</th>
                    <th class="p-4 text-left">Gender</th>
                    <th class="p-4 text-left">Confidence</th>
                    <th class="p-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($profiles as $p): ?>
                    <tr>
                        <td class="p-4 font-medium capitalize">
                            <?= $p['name'] ?>
                        </td>
                        <td class="p-4">
                            <?= $p['gender'] ?>
                        </td>
                        <td class="p-4">
                            <?= round($p['probability'] * 100) ?>%
                        </td>
                        <td class="p-4 text-right">
                            <a href="profile-detail.php?id=<?= $p['id'] ?>" class="text-blue-500 hover:underline">Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex gap-2">
        <a href="?page=<?= max(1, $page - 1) ?>" class="px-4 py-2 bg-white border rounded text-sm">Prev</a>
        <a href="?page=<?= $page + 1 ?>" class="px-4 py-2 bg-white border rounded text-sm">Next</a>
    </div>
</div>