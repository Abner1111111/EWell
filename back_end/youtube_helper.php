<?php

function getYoutubeVideoId($url) {
    $video_id = '';
    // Regular expressions to match various YouTube URL formats
    $patterns = [
        '/youtube\.com\/watch\?v=([^\&\?\/]+)/',
        '/youtube\.com\/embed\/([^\&\?\/]+)/',
        '/youtube\.com\/v\/([^\&\?\/]+)/',
        '/youtu\.be\/([^\&\?\/]+)/',
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $matches)) {
            $video_id = $matches[1];
            break;
        }
    }

    return $video_id;
}

function fetchUrl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        return false;
    }

    return $response;
}

function getYoutubeVideoDetails($url) {
    $video_id = getYoutubeVideoId($url);
    if (empty($video_id)) {
        return [
            'success' => false,
            'message' => 'Invalid YouTube URL. Please make sure you\'re using a valid YouTube video URL.'
        ];
    }

    // Using oEmbed endpoint (doesn't require API key)
    $oembed_url = "https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v={$video_id}&format=json";
    
    $response = fetchUrl($oembed_url);
    if ($response === false) {
        return [
            'success' => false,
            'message' => 'Failed to fetch video details. Please check your internet connection and try again.'
        ];
    }

    $data = json_decode($response, true);
    if (!$data) {
        return [
            'success' => false,
            'message' => 'Failed to parse video details. The video might be private or unavailable.'
        ];
    }

    // Get high quality thumbnail
    $thumbnail_url = "https://img.youtube.com/vi/{$video_id}/maxresdefault.jpg";
    
    // Check if high quality thumbnail exists
    $ch = curl_init($thumbnail_url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Fallback to medium quality if high quality doesn't exist
    if ($httpCode !== 200) {
        $thumbnail_url = "https://img.youtube.com/vi/{$video_id}/mqdefault.jpg";
    }

    return [
        'success' => true,
        'data' => [
            'title' => $data['title'],
            'video_url' => "https://www.youtube.com/embed/{$video_id}",
            'thumbnail_url' => $thumbnail_url,
            'video_id' => $video_id
        ]
    ];
}

// Function to handle AJAX requests
if (isset($_POST['action']) && $_POST['action'] === 'fetch_video_details') {
    header('Content-Type: application/json');
    $url = isset($_POST['url']) ? $_POST['url'] : '';
    echo json_encode(getYoutubeVideoDetails($url));
    exit;
}
?> 