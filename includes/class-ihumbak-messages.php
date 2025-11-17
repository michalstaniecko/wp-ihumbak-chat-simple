<?php
/**
 * Messages Custom Post Type Class
 *
 * @package Ihumbak_Chat
 */

class Ihumbak_Messages {

    /**
     * Register custom post type
     */
    public function register_cpt() {
        $labels = array(
            'name'               => __( 'Messages', 'ihumbak-chat' ),
            'singular_name'      => __( 'Message', 'ihumbak-chat' ),
            'menu_name'          => __( 'Messages', 'ihumbak-chat' ),
            'add_new'            => __( 'Add New', 'ihumbak-chat' ),
            'add_new_item'       => __( 'Add New Message', 'ihumbak-chat' ),
            'edit_item'          => __( 'Edit Message', 'ihumbak-chat' ),
            'new_item'           => __( 'New Message', 'ihumbak-chat' ),
            'view_item'          => __( 'View Message', 'ihumbak-chat' ),
            'search_items'       => __( 'Search Messages', 'ihumbak-chat' ),
            'not_found'          => __( 'No messages found', 'ihumbak-chat' ),
            'not_found_in_trash' => __( 'No messages found in Trash', 'ihumbak-chat' ),
        );

        $args = array(
            'labels'              => $labels,
            'public'              => false,
            'show_ui'             => false,
            'show_in_menu'        => false,
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'supports'            => array( 'title', 'editor' ),
            'has_archive'         => false,
            'rewrite'             => false,
            'query_var'           => false,
        );

        register_post_type( 'ihumbak_message', $args );
    }

    /**
     * Create a new message
     *
     * @param array $data Message data.
     * @return int|WP_Error Post ID on success, WP_Error on failure.
     */
    public static function create_message( $data ) {
        $email = isset( $data['email'] ) ? $data['email'] : '';
        $message = isset( $data['message'] ) ? $data['message'] : '';

        if ( empty( $email ) || empty( $message ) ) {
            return new WP_Error( 'invalid_data', __( 'Email and message are required.', 'ihumbak-chat' ) );
        }

        // Create post
        $post_id = wp_insert_post( array(
            'post_type'    => 'ihumbak_message',
            'post_title'   => sprintf( __( 'Message from %s', 'ihumbak-chat' ), $email ),
            'post_content' => $message,
            'post_status'  => 'pending', // New messages are pending
        ) );

        if ( is_wp_error( $post_id ) ) {
            return $post_id;
        }

        // Save metadata
        update_post_meta( $post_id, 'ihumbak_email', $email );
        update_post_meta( $post_id, 'ihumbak_ip', Ihumbak_Security::get_client_ip() );
        update_post_meta( $post_id, 'ihumbak_user_agent', Ihumbak_Security::get_user_agent() );
        update_post_meta( $post_id, 'ihumbak_referer', Ihumbak_Security::get_referer() );
        update_post_meta( $post_id, 'ihumbak_read', 0 );
        update_post_meta( $post_id, 'ihumbak_responded', 0 );
        update_post_meta( $post_id, 'ihumbak_consent_given', isset( $data['consent'] ) ? 1 : 0 );

        return $post_id;
    }

    /**
     * Get messages
     *
     * @param array $args Query arguments.
     * @return WP_Query
     */
    public static function get_messages( $args = array() ) {
        $defaults = array(
            'post_type'      => 'ihumbak_message',
            'posts_per_page' => 20,
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        $args = wp_parse_args( $args, $defaults );
        return new WP_Query( $args );
    }

    /**
     * Mark message as read
     *
     * @param int $post_id Post ID.
     * @return bool
     */
    public static function mark_as_read( $post_id ) {
        update_post_meta( $post_id, 'ihumbak_read', 1 );
        wp_update_post( array(
            'ID'          => $post_id,
            'post_status' => 'publish',
        ) );
        return true;
    }

    /**
     * Delete message
     *
     * @param int $post_id Post ID.
     * @return bool
     */
    public static function delete_message( $post_id ) {
        return wp_delete_post( $post_id, true ) !== false;
    }

    /**
     * Mark message as responded
     *
     * @param int $post_id Post ID.
     * @return bool
     */
    public static function mark_as_responded( $post_id ) {
        update_post_meta( $post_id, 'ihumbak_responded', 1 );
        return true;
    }
}
