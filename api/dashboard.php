<?php
// Include your header which handles the session check
include "header.php";

// 2. API Helper: This acts as the bridge to your Backend Repo
function getApiData($endpoint)
{
    // Replace with your actual API domain
    $baseUrl = "https://profile-intelligence-api.pxxl.click";
    $ch = curl_init($baseUrl . $endpoint);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Pass cookies (if needed for authentication)
    curl_setopt($ch, CURLOPT_COOKIE, $_SERVER['HTTP_COOKIE'] ?? '');

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode !== 200) {
        return []; // Return empty if API fails
    }

    return json_decode($response, true) ?? [];
}

// 3. Fetch Data from your Backend API (Not the DB!)
$stats = getApiData("/api/v1/stats"); // Expected: ["total" => 50]
$recent_hits = getApiData("/api/v1/profiles?limit=5"); // Expected: array of profiles
?>

<div class="max-w-6xl mx-auto p-6">
    <header class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Welcome, Habiba</h2>
        <p class="text-gray-500">System overview fetched from your API.</p>
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

    <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
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
                            <td class="py-3 font-medium capitalize"><?= htmlspecialchars($hit['name'] ?? 'N/A') ?></td>
                            <td class="py-3 text-green-600">Success</td>
                            <td class="py-3 text-right text-gray-500">
                                <?= isset($hit['processed_at']) ? date('M d, H:i', strtotime($hit['processed_at'])) : 'N/A' ?>
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
// Include footer
include __DIR__ . '/../footer.php';
?>