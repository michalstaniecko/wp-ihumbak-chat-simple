<?php
/**
 * Main Plugin Class
 *
 * @package Ihumbak_Chat
 */

class Ihumbak_Chat {

    /**
     * Initialize the plugin
     */
    public function init() {
        // Load dependencies
        $this->load_dependencies();
        
        // Register hooks
        $this->register_hooks();
        
        // Load text domain
        add_action( 'init', array( $this, 'load_textdomain' ) );
    }

    /**
     * Load required files
     */
    private function load_dependencies() {
        require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-security.php';
        require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-messages.php';
        require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-settings-defaults.php';
        require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-settings-validator.php';
        require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-settings.php';
        require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-rest-api.php';
        require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-email.php';
        require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-admin.php';
    }

    /**
     * Register WordPress hooks
     */
    private function register_hooks() {
        // Initialize CPT
        $messages = new Ihumbak_Messages();
        add_action( 'init', array( $messages, 'register_cpt' ) );
        
        // Initialize REST API
        $rest_api = new Ihumbak_REST_API();
        add_action( 'rest_api_init', array( $rest_api, 'register_routes' ) );
        
        // Initialize admin
        if ( is_admin() ) {
            $admin = new Ihumbak_Admin();
            add_action( 'admin_menu', array( $admin, 'add_admin_menu' ) );
            add_action( 'admin_enqueue_scripts', array( $admin, 'enqueue_admin_assets' ) );
        }
        
        // Enqueue frontend assets
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        
        // Add widget to footer
        add_action( 'wp_footer', array( $this, 'render_widget' ) );
    }

    /**
     * Load plugin text domain for translations
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'ihumbak-chat',
            false,
            dirname( IHUMBAK_CHAT_PLUGIN_BASENAME ) . '/languages'
        );
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        // Enqueue CSS
        wp_enqueue_style(
            'ihumbak-chat-widget',
            IHUMBAK_CHAT_PLUGIN_URL . 'public/css/ihumbak-widget.css',
            array(),
            IHUMBAK_CHAT_VERSION
        );

        // Enqueue JavaScript
        wp_enqueue_script(
            'ihumbak-chat-widget',
            IHUMBAK_CHAT_PLUGIN_URL . 'public/js/ihumbak-widget.js',
            array(),
            IHUMBAK_CHAT_VERSION,
            true
        );

        // Localize script with data
        $settings = Ihumbak_Settings::get_instance();
        wp_localize_script(
            'ihumbak-chat-widget',
            'ihumbakChat',
            array(
                'ajaxUrl'              => rest_url( 'ihumbak-chat/v1/send-message' ),
                'nonce'                => wp_create_nonce( 'wp_rest' ),
                'recaptchaSiteKey'     => $settings->get_option( 'ihumbak_recaptcha_site_key', '' ),
                'widgetColor'          => $settings->get_option( 'ihumbak_widget_color', '#007bff' ),
                'widgetPosition'       => $settings->get_option( 'ihumbak_widget_position', 'bottom-right' ),
                'widgetTitle'          => $settings->get_option( 'ihumbak_widget_title', 'Get in Touch' ),
                'introText'            => $settings->get_option( 'ihumbak_intro_text', '' ),
                'emailLabel'           => $settings->get_option( 'ihumbak_email_label', 'Your Email' ),
                'emailPlaceholder'     => $settings->get_option( 'ihumbak_email_placeholder', 'your@email.com' ),
                'emailErrorMessage'    => $settings->get_option( 'ihumbak_email_error_message', 'Please enter a valid email address.' ),
                'messageLabel'         => $settings->get_option( 'ihumbak_message_label', 'Message' ),
                'messagePlaceholder'   => $settings->get_option( 'ihumbak_message_placeholder', 'Your message...' ),
                'messageErrorMessage'  => $settings->get_option( 'ihumbak_message_error_message', 'Message must be at least 5 characters.' ),
                'consentText'          => $settings->get_option( 'ihumbak_consent_text', 'Wyrażam zgodę na przetwarzanie moich danych osobowych w celu udzielenia odpowiedzi na przesłaną wiadomość.' ),
                'consentErrorMessage'  => $settings->get_option( 'ihumbak_consent_error_message', 'You must consent to data processing.' ),
                'headerFontColor'      => $settings->get_option( 'ihumbak_header_font_color', '#ffffff' ),
                'submitButtonText'     => $settings->get_option( 'ihumbak_submit_button_text', 'Send' ),
            )
        );

        // Enqueue reCAPTCHA if key is set
        $recaptcha_key = $settings->get_option( 'ihumbak_recaptcha_site_key', '' );
        if ( ! empty( $recaptcha_key ) ) {
            wp_enqueue_script(
                'google-recaptcha',
                'https://www.google.com/recaptcha/api.js?render=' . esc_attr( $recaptcha_key ),
                array(),
                null,
                true
            );
        }
    }

    /**
     * Render widget HTML in footer
     */
    public function render_widget() {
        // Check if widget should be shown only to admins
        $settings = Ihumbak_Settings::get_instance();
        $admin_only = $settings->get_option( 'ihumbak_widget_admin_only', 0 );
        
        if ( $admin_only && ! current_user_can( 'manage_options' ) ) {
            return; // Don't show widget to non-admins
        }
        
        include IHUMBAK_CHAT_PLUGIN_DIR . 'public/templates/widget.php';
    }
}
