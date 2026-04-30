<?php
// Include your header which handles the session check
include "header.php";

function getApiData($endpoint) {
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

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Recent API Hits</h3>
        </div>
        <table class="w-full text-left">
            <tbody class="divide-y divide-gray-100 text-sm">
                <?php if (!empty($recent)): foreach ($recent as $row): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-semibold text-gray-700 capitalize"><?= htmlspecialchars($row['name']) ?></td>
                    <td class="px-6 py-4 text-gray-500"><?= htmlspecialchars($row['gender']) ?></td>
                    <td class="px-6 py-4 text-gray-400"><?= date('M d, H:i', strtotime($row['processed_at'])) ?></td>
                </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="3" class="px-6 py-4 text-center">No data found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
// The ../ tells PHP to go up out of the 'api' folder and into the root
include('../footer.php'); 
?>