function fetch_youtube_videos($search_query) {
    $api_key = 'AIzaSyB_W-liYrMa7n0u9Vaqd4M05Uz_l1xTWjY';
    $channel_id = 'UCfEZiN3ALag4u7cq6NpmkHA'; 
    $search_query = urlencode($search_query);
    
    $api_url = "https://www.googleapis.com/youtube/v3/search?key=$api_key&channelId=$channel_id&part=snippet&type=video&q=$search_query";
    
    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        return [];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    return $data['items'] ?? [];
} 