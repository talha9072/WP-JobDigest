<?php
// Add "Export Jobs" under WP JobDigest settings menu
add_action('admin_menu', 'wpjd_add_export_page');

function wpjd_add_export_page() {
    add_submenu_page(
        'wpjd-settings',
        'Export Jobs',
        'Export Jobs',
        'manage_options',
        'wpjd-export',
        'wpjd_render_export_page'
    );
}

function wpjd_render_export_page() {
    ?>
    <div class="wrap">
        <h1>📤 Export Jobs</h1>
        <form method="post">
            <p>This will fetch and download the latest jobs based on your saved keyword, location, and job count settings.</p>
            <input type="submit" name="wpjd_export_jobs" class="button button-primary" value="Fetch & Download Jobs">
        </form>
    </div>
    <?php

    if (isset($_POST['wpjd_export_jobs'])) {
        $keyword  = get_option('wpjd_job_keyword');
        $location = get_option('wpjd_job_location');
        $count    = intval(get_option('wpjd_job_count', 10));

        require_once WPJD_PLUGIN_DIR . 'includes/fetch-jobs.php';
        require_once WPJD_PLUGIN_DIR . 'includes/generate-file.php';

        // Clean any previous output to avoid download corruption
        while (ob_get_level()) {
            ob_end_clean();
        }

        $jobs = wpjd_fetch_jobs_now($keyword, $location, $count, false); // debug = false

        if (!empty($jobs)) {
            wpjd_generate_and_download_file($jobs);
            exit;
        } else {
            echo '<div class="notice notice-warning"><p>No jobs found based on your filters.</p></div>';
        }
    }
}
