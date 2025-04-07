<?php
// Hook our menu into the WordPress admin
add_action('admin_menu', 'wpjd_add_settings_menu');

function wpjd_add_settings_menu() {
    add_menu_page(
        'WP JobDigest Settings',
        'WP JobDigest',
        'manage_options',
        'wpjd-settings',
        'wpjd_render_settings_page',
        'dashicons-list-view',
        90
    );
}

// Render the settings page content
function wpjd_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>🧩 WP JobDigest Plugin Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('wpjd_settings_group');
            do_settings_sections('wpjd-settings');
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}

// Register settings
add_action('admin_init', 'wpjd_register_settings');

function wpjd_register_settings() {
    add_settings_section(
        'wpjd_main_section',
        'Job Alert Settings',
        null,
        'wpjd-settings'
    );

    add_settings_field(
        'wpjd_job_keyword',
        '🔍 Job Keyword',
        'wpjd_keyword_callback',
        'wpjd-settings',
        'wpjd_main_section'
    );
    register_setting('wpjd_settings_group', 'wpjd_job_keyword');

    add_settings_field(
        'wpjd_job_location',
        '📍 Job Location',
        'wpjd_location_callback',
        'wpjd-settings',
        'wpjd_main_section'
    );
    register_setting('wpjd_settings_group', 'wpjd_job_location');

    add_settings_field(
        'wpjd_notification_email',
        '📧 Notification Email (optional)',
        'wpjd_email_callback',
        'wpjd-settings',
        'wpjd_main_section'
    );
    register_setting('wpjd_settings_group', 'wpjd_notification_email');

    add_settings_field(
        'wpjd_job_count',
        '🔢 Number of Jobs to Fetch',
        'wpjd_job_count_callback',
        'wpjd-settings',
        'wpjd_main_section'
    );
    register_setting('wpjd_settings_group', 'wpjd_job_count');
}

// Field Callbacks
function wpjd_keyword_callback() {
    $value = esc_attr(get_option('wpjd_job_keyword'));
    echo '<input type="text" name="wpjd_job_keyword" value="' . $value . '" class="regular-text" />';
}

function wpjd_location_callback() {
    $value = esc_attr(get_option('wpjd_job_location'));
    echo '<input type="text" name="wpjd_job_location" value="' . $value . '" class="regular-text" />';
}

function wpjd_email_callback() {
    $value = esc_attr(get_option('wpjd_notification_email'));
    echo '<input type="email" name="wpjd_notification_email" value="' . $value . '" class="regular-text" placeholder="Leave blank to use admin email" />';
}

function wpjd_job_count_callback() {
    $value = esc_attr(get_option('wpjd_job_count', 10));
    echo '<input type="number" name="wpjd_job_count" value="' . $value . '" class="small-text" min="1" max="100" />';
}
