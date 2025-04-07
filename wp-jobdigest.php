<?php
/**
 * Plugin Name: WP JobDigest
 * Plugin URI: https://github.com/talha9072/WP-JobDigest
 * Description: Fetch remote job listings and export them as a .txt file based on selected keyword, location, and count.
 * Version: 1.0.1
 * Author: Talha Shahid
 * Author URI: https://talha-solutions.site/
 * License: GPL2
 * Text Domain: wp-jobdigest
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Define plugin constants
define( 'WPJD_VERSION', '1.0.1' );
define( 'WPJD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPJD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include required files
require_once WPJD_PLUGIN_DIR . 'includes/settings.php';
require_once WPJD_PLUGIN_DIR . 'includes/fetch-jobs.php';
require_once WPJD_PLUGIN_DIR . 'includes/export-page.php';
