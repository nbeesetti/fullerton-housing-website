<?php
/*
Plugin Name: Test File Upload
Description: Plugin to test file upload functionality in WordPress admin.
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
            <input type="file" name="uploaded_file" required />
            <?php submit_button('Upload File'); ?>
        </form>
    </div>
    <?php

    if (isset($_FILES['uploaded_file']) && current_user_can('manage_options')) {
        // Display a success message
        echo '<div class="notice notice-success"><p>File uploaded successfully!</p></div>';
    }
}
?>
