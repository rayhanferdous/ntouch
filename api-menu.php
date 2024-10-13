<?php
// Allow CORS
header("Access-Control-Allow-Origin: http://0.0.0.0:8000");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, api-key");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit; // Exit for preflight requests
}

// Endpoint to fetch the menu
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $apiKey = 'JFySrN9UWK1qzGq4KkgWepVt7y4QgcDz';
    $apiUrl = 'https://onlineorderingsecure.com/api/1.0/restaurant/19034/menu';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'api-key: ' . $apiKey
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch data', 'details' => $error]);
        exit;
    }

    curl_close($ch);

    if ($httpCode !== 200) {
        http_response_code($httpCode);
        echo json_encode(['error' => 'Failed to fetch data', 'details' => $response]);
        exit;
    }

    // Return the fetched data
    header('Content-Type: application/json');
    echo $response;
}
?>

