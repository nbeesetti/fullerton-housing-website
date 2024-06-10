<?php
/*
Plugin Name: Search Transcripts
Description: Plugin to search stored transcripts.
Version: 2.1
Author: FHA
*/

function handle_search_query() {
    if (isset($_GET['q'])) {
        global $wpdb;
        $query = sanitize_text_field($_GET['q']);
        $results = $wpdb->get_results($wpdb->prepare("
            SELECT id, video_id, combined_text, prefix_sum_lengths, timestamps
            FROM {$wpdb->prefix}video_transcripts
            WHERE combined_text LIKE %s
        ", '%' . $wpdb->esc_like($query) . '%'));

        $response = array();

        foreach ($results as $result) {
            $video_id = $result->video_id;
            $combined_text = $result->combined_text;
            $prefix_sum_lengths = maybe_unserialize($result->prefix_sum_lengths);
            $timestamps = maybe_unserialize($result->timestamps);

            // Find all positions of the query in the combined text
            $positions = [];
            $lastPos = 0;
            while (($lastPos = strpos($combined_text, $query, $lastPos)) !== false) {
                $positions[] = $lastPos;
                $lastPos += strlen($query);
            }

            // Get timestamps for all positions
            $video_timestamps = [];
            foreach ($positions as $position) {
                $timestamp = find_nearest_timestamp($prefix_sum_lengths, $timestamps, $position);
                if (!in_array($timestamp, $video_timestamps)) {
                    $video_timestamps[] = $timestamp;
                }
            }

            if (!empty($video_timestamps)) {
                $response[] = array(
                    'video_id' => $video_id,
                    'timestamps' => $video_timestamps
                );
            }
        }

        // If no results found, return an empty array or a specific message
        if (empty($response)) {
            wp_send_json_error('No results found.');
        } else {
            wp_send_json_success($response);
        }

        wp_die();
    } else {
        wp_send_json_error('No query parameter provided.');
    }
}

add_action('wp_ajax_handle_search_query', 'handle_search_query');
add_action('wp_ajax_nopriv_handle_search_query', 'handle_search_query');

function find_nearest_timestamp($prefix_sum_lengths, $timestamps, $position) {
    $left = 0;
    $right = count($prefix_sum_lengths) - 1;

    if ($position <= $prefix_sum_lengths[$left]) {
        return $timestamps[$left];
    }

    if ($position >= $prefix_sum_lengths[$right]) {
        return $timestamps[$right];
    }

    while ($left <= $right) {
        $mid = intdiv($left + $right, 2);

        if ($prefix_sum_lengths[$mid] == $position) {
            return $timestamps[$mid];
        } elseif ($prefix_sum_lengths[$mid] < $position) {
            $left = $mid + 1;
        } else {
            $right = $mid - 1;
        }
    }

    // If exact match not found, find the nearest index
    if ($left >= count($prefix_sum_lengths)) {
        $left = count($prefix_sum_lengths) - 1;
    }

    if ($right < 0) {
        $right = 0;
    }

    // Determine which of the closest two indices is nearer
    $nearest_index = ($position - $prefix_sum_lengths[$right]) <= ($prefix_sum_lengths[$left] - $position) ? $right : $left;

    return $timestamps[$nearest_index];
}
?>
