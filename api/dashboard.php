<?php
// Include your header which handles the session check
include "header.php";

function getApiData($endpoint)
{
    $url = "https://profile-intelligence-api.pxxl.click" . $endpoint;
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Pass cookies for authentication
    curl_setopt($ch, CURLOPT_COOKIE, $_SERVER['HTTP_COOKIE'] ?? '');

    $response = curl_exec($ch);

    // Check if the connection failed
    if (curl_errno($ch)) {
        return ['status' => 'error', 'message' => 'Connection to API failed: ' . curl_error($ch)];
    }

    // No need for curl_close in modern PHP (it cleans itself up)

    $data = json_decode($response, true);

    // Check if JSON decoding worked
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['status' => 'error', 'message' => 'Invalid API response'];
    }

    return $data;
}
// Fetch data
$stats = getApiData("/api/v1/stats"); // Ensure your API returns {"total": 50}
$recent = getApiData("/api/v1/profiles?limit=5"); // Ensure your API returns a JSON list
// Fetch the 5 most recent hits
$sql_recent = "SELECT * FROM profiles ORDER BY processed_at DESC LIMIT 5";
$recent_hits = $conn->query($sql_recent)->fetchAll();
?>

<div class="max-w-6xl mx-auto">
    <header class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Welcome, Habiba</h2>
        <p class="text-gray-500">System overview via API.</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <p class="text-gray-500 text-sm font-semibold uppercase">Total Profiles</p>
            <p class="text-4xl font-black text-slate-900"><?= $stats['total'] ?? 0 ?></p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <p class="text-gray-500 text-sm font-semibold uppercase">API Status</p>
            <p class="text-xl font-bold text-green-500">● Live</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-bold mb-4">Recent API Hits</h3>

        <?php if (!empty($recent_hits)): ?>
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-400 text-sm border-b">
                        <th class="pb-2">Name</th>
                        <th class="pb-2">Status</th>
                        <th class="pb-2 text-right">Time</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php foreach ($recent_hits as $hit): ?>
                        <tr class="border-b last:border-none">
                            <td class="py-3 font-medium capitalize"><?= htmlspecialchars($hit['name']) ?></td>
                            <td class="py-3 text-green-600">Success</td>
                            <td class="py-3 text-right text-gray-500">
                                <?= date('M d, H:i', strtotime($hit['processed_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-500">No recent API activity found.</p>
        <?php endif; ?>
    </div>
</div>

<?php
// Use this for the footer
include __DIR__ . '/../footer.php';
?>