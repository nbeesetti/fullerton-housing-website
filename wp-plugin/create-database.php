<?php
/*
Plugin Name: Create Transcript Table in Database
Description: This plugin creates the transcript MySQL table once when activated.
Version: 1.0
Author: FHA
*/

function create_transcript_tables() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'video_transcripts';

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        video_id varchar(255) NOT NULL,
        combined_text longtext NOT NULL,
        prefix_sum_lengths longtext NOT NULL,
        timestamps longtext NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'create_transcript_tables');
?>