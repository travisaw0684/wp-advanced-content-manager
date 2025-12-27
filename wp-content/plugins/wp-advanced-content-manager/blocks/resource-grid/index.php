<?php

namespace ACM;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function register_resource_grid_block() {

    register_block_type(
        __DIR__,
        [
            'render_callback' => __NAMESPACE__ . '\\render_resource_grid_block',
        ]
    );
}

add_action( 'init', __NAMESPACE__ . '\\register_resource_grid_block' );

function render_resource_grid_block( $attributes ) {

    $atts = shortcode_atts(
        [
            'posts_per_page' => $attributes['postsPerPage'] ?? 6,
            'category'       => $attributes['defaultCategory'] ?? '',
        ],
        []
    );

    ob_start();
    include ACM_PATH . 'templates/resource-grid.php';
    return ob_get_clean();
}
