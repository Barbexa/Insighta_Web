<?php
include "header.php";

function getApiData($endpoint)
{
    $baseUrl = "https://profile-intelligence-api.pxxl.click";
    $ch = curl_init($baseUrl . $endpoint);

    // We must pass the Authorization header because your API requires it
    $headers = [
        "X-API-Version: 1",
        "Authorization: Bearer " . ($_COOKIE['auth_token'] ?? '')
    ];

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Added headers

    $response = curl_exec($ch);
    $data = json_decode($response, true);
    return $data['data'] ?? []; // Return the 'data' array specifically
}

$search = $_GET['q'] ?? '';
$results = [];

if (!empty($search)) {
    // Call the API with the 'q' parameter
    $results = getApiData("/api/v1/profiles?q=" . urlencode($search));
}
?>

<form method="GET" action="/search" class="mb-8 flex gap-4">
    <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Enter name..."
        class="flex-1 p-3 border rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 outline-none">
    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold">Search</button>
</form>

<div class="grid gap-4">
    <?php if (!empty($results)): ?>
        <?php foreach ($results as $res): ?>
            <div class="bg-white p-4 rounded-lg border shadow-sm flex justify-between items-center">
                <div>
                    <p class="font-bold capitalize"><?= htmlspecialchars($res['name'] ?? 'Unknown') ?></p>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($res['gender'] ?? 'N/A') ?> •
                        <?= htmlspecialchars($res['country_id'] ?? 'N/A') ?></p>
                </div>
                <a href="profile-detail.php?id=<?= $res['id'] ?>" class="text-blue-600 font-medium">View Detail →</a>
            </div>
        <?php endforeach; ?>
    <?php elseif (!empty($search)): ?>
        <p class="text-gray-500 italic">No results found for "<?= htmlspecialchars($search) ?>"</p>
    <?php endif; ?>
</div>