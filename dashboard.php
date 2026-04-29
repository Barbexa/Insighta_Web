<?php
require "db.php";
include "header.php";

// Real-time metrics
$total = $conn->query("SELECT COUNT(*) FROM profiles")->fetchColumn();
$recent = $conn->query("SELECT * FROM profiles ORDER BY processed_at DESC LIMIT 5")->fetchAll();
?>

<div class="max-w-6xl mx-auto">
    <header class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Welcome, Habiba</h2>
        <p class="text-gray-500">Here’s what’s happening with your profile data.</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <p class="text-gray-500 text-sm font-semibold uppercase">Total Profiles</p>
            <p class="text-4xl font-black text-slate-900"><?= $total ?></p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <p class="text-gray-500 text-sm font-semibold uppercase">API Status</p>
            <p class="text-xl font-bold text-green-500">● Active (v1)</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <p class="text-gray-500 text-sm font-semibold uppercase">Database</p>
            <p class="text-xl font-bold text-blue-500">Railway Live</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Recent API Hits</h3>
            <a href="profiles.php" class="text-blue-600 text-sm hover:underline">View All</a>
        </div>
        <table class="w-full text-left">
            <tbody class="divide-y divide-gray-100 text-sm">
                <?php foreach ($recent as $row): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-gray-700 capitalize"><?= $row['name'] ?></td>
                        <td class="px-6 py-4 text-gray-500"><?= $row['gender'] ?></td>
                        <td class="px-6 py-4 text-gray-400"><?= date('M d, H:i', strtotime($row['processed_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include "footer.php"; ?>