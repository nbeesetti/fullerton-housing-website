<?php
/*
Plugin Name: Upload YouTube Transcripts
Description: Plugin to upload YouTube Transcript JSON files in WordPress admin for Search.
Version: 1.0
Author: FHA
*/

function register_upload_transcript_page() {
    add_menu_page('Upload YouTube Transcript', 'Upload YouTube Transcript', 'manage_options', 'upload-youtube-transcript', 'upload_transcript_page');
}
add_action('admin_menu', 'register_upload_transcript_page');

function upload_transcript_page() {
    ?>
    <div class="wrap">
        <h2>Upload YouTube Transcript JSON File for Search</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="transcript_json" accept=".json" required />
            <?php submit_button('Upload JSON'); ?>
        </form>
    </div>
    <?php

if (isset($_FILES['transcript_json']) && current_user_can('manage_options')) {
    $file = $_FILES['transcript_json']['tmp_name'];
    $json = file_get_contents($file);
    $data = json_decode($json, true);

    if ($data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'video_transcripts';

        $video_id = sanitize_text_field($data['videoId']);
        $combined_text = sanitize_textarea_field($data['combinedText']);
        $prefix_sum_lengths = maybe_serialize($data['prefixSumTextLengths']);
        $timestamps = maybe_serialize($data['timestamps']);

        $data_to_insert = array(
            'video_id' => $video_id,
            'combined_text' => $combined_text,
            'prefix_sum_lengths' => $prefix_sum_lengths,
            'timestamps' => $timestamps,
        );

        $insert_result = $wpdb->insert($table_name, $data_to_insert);

        if ($insert_result === false) {
            echo '<div class="notice notice-error"><p>Error: ' . $wpdb->last_error . '</p></div>';
        } else {
            echo '<div class="notice notice-success"><p>Transcript uploaded successfully!</p></div>';
        }
    } else {
        echo '<div class="notice notice-error"><p>Invalid JSON file.</p></div>';
    }
}

}
?>