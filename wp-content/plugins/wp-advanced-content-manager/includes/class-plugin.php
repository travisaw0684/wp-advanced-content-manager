<?php

namespace ACM;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Plugin {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        $this->includes();
        $this->hooks();
    }

    private function includes() {
        // Future includes go here
        require_once ACM_PATH . 'includes/class-cpt.php';
        require_once ACM_PATH . 'includes/class-meta.php';
        require_once ACM_PATH . 'includes/class-ajax.php';
    }

    private function hooks() {
        new CPT();
        new Meta();
        new Ajax();

        $this->register_blocks();

        register_activation_hook( ACM_PATH . 'wp-advanced-content-manager.php', [ $this, 'activate' ] );
        register_deactivation_hook( ACM_PATH . 'wp-advanced-content-manager.php', [ $this, 'deactivate' ] );
    }

    private function register_blocks() {
        require_once ACM_PATH . 'blocks/resource-grid/index.php';
    }
    

    public function activate() {
        // Flush rewrite rules after CPTs are registered
        flush_rewrite_rules();
    }

    public function deactivate() {
        flush_rewrite_rules();
    }
}
