<?php
/**
 * Plugin Name: iHumbak Chat - Quick Admin Communication
 * Plugin URI: https://github.com/yourusername/wp-ihumbak-chat
 * Description: Enables quick communication with admin via a floating chat widget. Simple email and message form with reCAPTCHA protection.
 * Version: 1.6.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ihumbak-chat
 * Domain Path: /languages
 * Requires at least: 5.9
 * Requires PHP: 7.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'IHUMBAK_CHAT_VERSION', '1.6.0' );
define( 'IHUMBAK_CHAT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'IHUMBAK_CHAT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'IHUMBAK_CHAT_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Require main plugin class
require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-chat.php';

/**
 * Initialize the plugin
 */
function ihumbak_chat_init() {
    $plugin = new Ihumbak_Chat();
    $plugin->init();
}
add_action( 'plugins_loaded', 'ihumbak_chat_init' );

/**
 * Activation hook
 */
function ihumbak_chat_activate() {
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Set default options
    if ( ! get_option( 'ihumbak_admin_email' ) ) {
        update_option( 'ihumbak_admin_email', get_option( 'admin_email' ) );
    }
    if ( ! get_option( 'ihumbak_spam_limit' ) ) {
        update_option( 'ihumbak_spam_limit', 5 );
    }
    if ( ! get_option( 'ihumbak_enable_notifications' ) ) {
        update_option( 'ihumbak_enable_notifications', 1 );
    }
    if ( ! get_option( 'ihumbak_widget_position' ) ) {
        update_option( 'ihumbak_widget_position', 'bottom-right' );
    }
    if ( ! get_option( 'ihumbak_widget_color' ) ) {
        update_option( 'ihumbak_widget_color', '#007bff' );
    }
    if ( ! get_option( 'ihumbak_widget_title' ) ) {
        update_option( 'ihumbak_widget_title', 'Get in Touch' );
    }
    if ( ! get_option( 'ihumbak_consent_text' ) ) {
        update_option( 'ihumbak_consent_text', 'Wyrażam zgodę na przetwarzanie moich danych osobowych w celu udzielenia odpowiedzi na przesłaną wiadomość.' );
    }
    if ( ! get_option( 'ihumbak_widget_admin_only' ) ) {
        update_option( 'ihumbak_widget_admin_only', 0 );
    }
    if ( ! get_option( 'ihumbak_header_font_color' ) ) {
        update_option( 'ihumbak_header_font_color', '#ffffff' );
    }
    if ( ! get_option( 'ihumbak_message_label' ) ) {
        update_option( 'ihumbak_message_label', 'Message' );
    }
    if ( ! get_option( 'ihumbak_message_placeholder' ) ) {
        update_option( 'ihumbak_message_placeholder', 'Your message...' );
    }
    if ( ! get_option( 'ihumbak_submit_button_text' ) ) {
        update_option( 'ihumbak_submit_button_text', 'Send' );
    }
}
register_activation_hook( __FILE__, 'ihumbak_chat_activate' );

/**
 * Deactivation hook
 */
function ihumbak_chat_deactivate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ihumbak_chat_deactivate' );
