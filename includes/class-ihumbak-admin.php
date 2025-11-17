<?php
/**
 * Admin Panel Class
 *
 * @package Ihumbak_Chat
 */

class Ihumbak_Admin {

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // Main menu
        add_menu_page(
            __( 'iHumbak Chat', 'ihumbak-chat' ),
            __( 'iHumbak Chat', 'ihumbak-chat' ),
            'manage_options',
            'ihumbak-messages',
            array( $this, 'render_messages_page' ),
            'dashicons-email',
            30
        );

        // Messages submenu
        add_submenu_page(
            'ihumbak-messages',
            __( 'Messages', 'ihumbak-chat' ),
            __( 'Messages', 'ihumbak-chat' ),
            'manage_options',
            'ihumbak-messages',
            array( $this, 'render_messages_page' )
        );

        // Settings submenu
        add_submenu_page(
            'ihumbak-messages',
            __( 'Settings', 'ihumbak-chat' ),
            __( 'Settings', 'ihumbak-chat' ),
            'manage_options',
            'ihumbak-settings',
            array( $this, 'render_settings_page' )
        );
    }

    /**
     * Enqueue admin assets
     *
     * @param string $hook Current admin page hook.
     */
    public function enqueue_admin_assets( $hook ) {
        if ( strpos( $hook, 'ihumbak' ) === false ) {
            return;
        }

        wp_enqueue_style(
            'ihumbak-admin-style',
            IHUMBAK_CHAT_PLUGIN_URL . 'admin/css/admin-style.css',
            array(),
            IHUMBAK_CHAT_VERSION
        );

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
    }

    /**
     * Render messages page
     */
    public function render_messages_page() {
        // Handle actions
        if ( isset( $_GET['action'] ) && isset( $_GET['id'] ) ) {
            $action = sanitize_text_field( wp_unslash( $_GET['action'] ) );
            $id = absint( $_GET['id'] );

            if ( 'view' === $action ) {
                include IHUMBAK_CHAT_PLUGIN_DIR . 'admin/pages/message-view.php';
                return;
            } elseif ( 'delete' === $action && isset( $_GET['_wpnonce'] ) ) {
                if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'delete_message_' . $id ) ) {
                    Ihumbak_Messages::delete_message( $id );
                    wp_safe_redirect( admin_url( 'admin.php?page=ihumbak-messages&deleted=1' ) );
                    exit;
                }
            } elseif ( 'mark_read' === $action && isset( $_GET['_wpnonce'] ) ) {
                if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'mark_read_' . $id ) ) {
                    Ihumbak_Messages::mark_as_read( $id );
                    wp_safe_redirect( admin_url( 'admin.php?page=ihumbak-messages&marked=1' ) );
                    exit;
                }
            } elseif ( 'mark_responded' === $action && isset( $_GET['_wpnonce'] ) ) {
                if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'mark_responded_' . $id ) ) {
                    Ihumbak_Messages::mark_as_responded( $id );
                    wp_safe_redirect( admin_url( 'admin.php?page=ihumbak-messages&action=view&id=' . $id . '&responded=1' ) );
                    exit;
                }
            }
        }

        include IHUMBAK_CHAT_PLUGIN_DIR . 'admin/pages/messages-list.php';
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        // Handle form submission
        if ( isset( $_POST['ihumbak_settings_submit'] ) && isset( $_POST['_wpnonce'] ) ) {
            if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'ihumbak_settings' ) ) {
                $settings = Ihumbak_Settings::get_instance();
                $result = $settings->save( $_POST );

                if ( is_wp_error( $result ) ) {
                    add_settings_error( 'ihumbak_settings', 'settings_error', $result->get_error_message(), 'error' );
                } else {
                    add_settings_error( 'ihumbak_settings', 'settings_updated', __( 'Settings saved successfully.', 'ihumbak-chat' ), 'success' );
                }
            }
        }

        include IHUMBAK_CHAT_PLUGIN_DIR . 'admin/pages/settings.php';
    }
}
