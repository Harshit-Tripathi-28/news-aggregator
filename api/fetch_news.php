<?php
header('Content-Type: application/json');
include("../config/env.php");

$apiKey = getEnvValue("GNEWS_API_KEY");

if (!$apiKey) {
    echo json_encode([
        "articles" => [],
        "error" => "API key not found"
    ]);
    exit();
}

$category = $_GET['category'] ?? 'general';
$category = urlencode($category);

$url = "https://gnews.io/api/v4/top-headlines?category=$category&lang=en&country=in&max=10&apikey=$apiKey";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode([
        "articles" => [],
        "error" => curl_error($ch)
    ]);
} else {
    echo $response;
}

curl_close($ch);
?>