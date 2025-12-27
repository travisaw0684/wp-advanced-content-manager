<?php
/**
 * Plugin Name: Advanced Content Manager
 * Description: A modular WordPress plugin for managing and displaying advanced content with AJAX filtering and blocks.
 * Version: 1.0.0
 * Author: Travis Walker
 * Text Domain: acm
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'ACM_PATH', plugin_dir_path( __FILE__ ) );
define( 'ACM_URL', plugin_dir_url( __FILE__ ) );
define( 'ACM_VERSION', '1.0.0' );

require_once ACM_PATH . 'includes/class-plugin.php';

function acm_init_plugin() {
    \ACM\Plugin::get_instance();
}

add_action( 'plugins_loaded', 'acm_init_plugin' );
