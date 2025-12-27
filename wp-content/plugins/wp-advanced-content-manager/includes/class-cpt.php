<?php

namespace ACM;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CPT {

    public function __construct() {
        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'init', [ $this, 'register_taxonomies' ] );
    }

    /**
     * Register Resources CPT
     */
    public function register_post_type() {

        $labels = [
            'name'               => __( 'Resources', 'acm' ),
            'singular_name'      => __( 'Resource', 'acm' ),
            'add_new'            => __( 'Add New', 'acm' ),
            'add_new_item'       => __( 'Add New Resource', 'acm' ),
            'edit_item'          => __( 'Edit Resource', 'acm' ),
            'new_item'           => __( 'New Resource', 'acm' ),
            'view_item'          => __( 'View Resource', 'acm' ),
            'search_items'       => __( 'Search Resources', 'acm' ),
            'not_found'          => __( 'No resources found', 'acm' ),
            'menu_name'          => __( 'Resources', 'acm' ),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => true,
            'rewrite'            => [ 'slug' => 'resources' ],
            'show_in_rest'       => true,
            'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
            'menu_icon'          => 'dashicons-media-document',
        ];

        register_post_type( 'acm_resource', $args );
    }

    /**
     * Register taxonomies
     */
    public function register_taxonomies() {

        // Resource Category
        register_taxonomy(
            'acm_resource_category',
            'acm_resource',
            [
                'label'        => __( 'Categories', 'acm' ),
                'rewrite'      => [ 'slug' => 'resource-category' ],
                'hierarchical' => true,
                'show_in_rest' => true,
            ]
        );

        // Resource Tag
        register_taxonomy(
            'acm_resource_tag',
            'acm_resource',
            [
                'label'        => __( 'Tags', 'acm' ),
                'rewrite'      => [ 'slug' => 'resource-tag' ],
                'hierarchical' => false,
                'show_in_rest' => true,
            ]
        );
    }
}
