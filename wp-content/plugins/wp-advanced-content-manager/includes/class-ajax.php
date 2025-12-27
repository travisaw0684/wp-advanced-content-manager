<?php

namespace ACM;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Ajax {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

        add_action( 'wp_ajax_acm_filter_resources', [ $this, 'filter_resources' ] );
        add_action( 'wp_ajax_nopriv_acm_filter_resources', [ $this, 'filter_resources' ] );

        add_shortcode( 'acm_resource_grid', [ $this, 'render_shortcode' ] );
    }

    public function enqueue_assets() {

        wp_enqueue_script(
            'acm-resource-grid',
            ACM_URL . 'assets/js/resource-grid.js',
            [ 'jquery' ],
            ACM_VERSION,
            true
        );

        wp_localize_script(
            'acm-resource-grid',
            'ACM',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'acm_ajax_nonce' ),
            ]
        );
    }

    public function render_shortcode() {
        ob_start();
        include ACM_PATH . 'templates/resource-grid.php';
        return ob_get_clean();
    }

    public function filter_resources() {

        check_ajax_referer( 'acm_ajax_nonce', 'nonce' );

        $tax_query = [];

        if ( ! empty( $_POST['category'] ) ) {
            $tax_query[] = [
                'taxonomy' => 'acm_resource_category',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $_POST['category'] ),
            ];
        }

        $meta_query = [];

        if ( ! empty( $_POST['content_type'] ) ) {
            $meta_query[] = [
                'key'   => 'acm_content_type',
                'value' => sanitize_text_field( $_POST['content_type'] ),
            ];
        }

        $query = new \WP_Query([
            'post_type'      => 'acm_resource',
            'posts_per_page' => 6,
            'tax_query'      => $tax_query,
            'meta_query'     => $meta_query,
        ]);

        $results = [];

        while ( $query->have_posts() ) {
            $query->the_post();

            $results[] = [
                'title' => get_the_title(),
                'link'  => get_permalink(),
                'type'  => get_post_meta( get_the_ID(), 'acm_content_type', true ),
            ];
        }

        wp_reset_postdata();

        wp_send_json_success( $results );
    }
}
