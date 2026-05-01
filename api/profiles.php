<?php
include "header.php";

// 1. Helper to talk to your Backend API
function getApiData($endpoint)
{
    $baseUrl = "https://profile-intelligence-api.pxxl.click";
    $ch = curl_init($baseUrl . $endpoint);

    // We MUST pass the version header and auth token
    $headers = [
        "X-API-Version: 1",
        "Authorization: Bearer " . ($_COOKIE['auth_token'] ?? '')
    ];

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $data = json_decode($response, true);

    // Return the data array if successful, or empty array
    return $data['data'] ?? [];
}

// 2. Prepare Variables
$page = (int) ($_GET['page'] ?? 1);
$gender = $_GET['gender'] ?? '';

// 3. Fetch from API (The API handles the database logic now)
$profiles = getApiData("/api/profiles?page=$page&limit=10&gender=" . urlencode($gender));
?>

<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Identified Profiles</h2>

        <form method="GET" class="flex gap-2">
            <select name="gender" class="border rounded px-3 py-1 text-sm bg-white">
                <option value="">All Genders</option>
                <option value="male" <?= $gender == 'male' ? 'selected' : '' ?>>Male</option>
                <option value="female" <?= $gender == 'female' ? 'selected' : '' ?>>Female</option>
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
                <?php if (!empty($profiles)): ?>
                    <?php foreach ($profiles as $p): ?>
                        <tr>
                            <td class="p-4 font-medium capitalize"><?= htmlspecialchars($p['name'] ?? 'N/A') ?></td>
                            <td class="p-4 capitalize"><?= htmlspecialchars($p['gender'] ?? 'N/A') ?></td>
                            <td class="p-4"><?= isset($p['probability']) ? round($p['probability'] * 100) . '%' : 'N/A' ?></td>
                            <td class="p-4 text-right">
                                <a href="profile-detail.php?id=<?= $p['id'] ?>" class="text-blue-500 hover:underline">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500">No profiles found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex gap-2">
        <a href="?page=<?= max(1, $page - 1) ?>&gender=<?= urlencode($gender) ?>"
            class="px-4 py-2 bg-white border rounded text-sm">Prev</a>
        <a href="?page=<?= $page + 1 ?>&gender=<?= urlencode($gender) ?>"
            class="px-4 py-2 bg-white border rounded text-sm">Next</a>
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>