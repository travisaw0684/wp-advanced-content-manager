<?php

namespace ACM;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Meta {

    private $fields = [];

    public function __construct() {

        $this->fields = [
            'acm_content_type' => [
                'label'   => 'Content Type',
                'type'    => 'select',
                'options' => [
                    'article' => 'Article',
                    'video'   => 'Video',
                    'pdf'     => 'PDF',
                ],
            ],
            'acm_reading_time' => [
                'label' => 'Reading Time (minutes)',
                'type'  => 'number',
            ],
            'acm_external_url' => [
                'label' => 'External URL',
                'type'  => 'url',
            ],
            'acm_featured' => [
                'label' => 'Featured',
                'type'  => 'checkbox',
            ],
        ];

        add_action( 'add_meta_boxes', [ $this, 'register_meta_box' ] );
        add_action( 'save_post', [ $this, 'save_meta' ] );
        add_action( 'init', [ $this, 'register_meta_for_rest' ] );
    }

    /**
     * Register meta box
     */
    public function register_meta_box() {
        add_meta_box(
            'acm_resource_meta',
            __( 'Resource Details', 'acm' ),
            [ $this, 'render_meta_box' ],
            'acm_resource',
            'normal',
            'default'
        );
    }

    /**
     * Render meta box fields
     */
    public function render_meta_box( $post ) {

        wp_nonce_field( 'acm_save_meta', 'acm_meta_nonce' );

        foreach ( $this->fields as $key => $field ) {

            $value = get_post_meta( $post->ID, $key, true );

            echo '<p>';
            echo '<label><strong>' . esc_html( $field['label'] ) . '</strong></label><br />';

            switch ( $field['type'] ) {

                case 'select':
                    echo '<select name="' . esc_attr( $key ) . '">';
                    foreach ( $field['options'] as $option_key => $option_label ) {
                        printf(
                            '<option value="%s" %s>%s</option>',
                            esc_attr( $option_key ),
                            selected( $value, $option_key, false ),
                            esc_html( $option_label )
                        );
                    }
                    echo '</select>';
                    break;

                case 'number':
                    echo '<input type="number" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" />';
                    break;

                case 'url':
                    echo '<input type="url" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" size="40" />';
                    break;

                case 'checkbox':
                    echo '<input type="checkbox" name="' . esc_attr( $key ) . '" value="1" ' . checked( $value, 1, false ) . ' />';
                    break;
            }

            echo '</p>';
        }
    }

    /**
     * Save meta securely
     */
    public function save_meta( $post_id ) {

        if ( ! isset( $_POST['acm_meta_nonce'] ) ||
             ! wp_verify_nonce( $_POST['acm_meta_nonce'], 'acm_save_meta' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( get_post_type( $post_id ) !== 'acm_resource' ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        foreach ( $this->fields as $key => $field ) {

            if ( $field['type'] === 'checkbox' ) {
                $value = isset( $_POST[ $key ] ) ? 1 : 0;
            } else {
                $value = isset( $_POST[ $key ] )
                    ? sanitize_text_field( $_POST[ $key ] )
                    : '';
            }

            update_post_meta( $post_id, $key, $value );
        }
    }

    /**
     * Expose meta to REST API
     */
    public function register_meta_for_rest() {

        foreach ( array_keys( $this->fields ) as $meta_key ) {
            register_post_meta(
                'acm_resource',
                $meta_key,
                [
                    'show_in_rest' => true,
                    'single'       => true,
                    'type'         => 'string',
                    'auth_callback'=> function() {
                        return current_user_can( 'edit_posts' );
                    },
                ]
            );
        }
    }
}
