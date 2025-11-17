<?php
/**
 * REST API Endpoints Class
 *
 * @package Ihumbak_Chat
 */

class Ihumbak_REST_API {

    /**
     * Register REST API routes
     */
    public function register_routes() {
        // Send message endpoint
        register_rest_route( 'ihumbak-chat/v1', '/send-message', array(
            'methods'             => 'POST',
            'callback'            => array( $this, 'send_message' ),
            'permission_callback' => '__return_true',
        ) );

        // Get settings endpoint
        register_rest_route( 'ihumbak-chat/v1', '/settings', array(
            'methods'             => 'GET',
            'callback'            => array( $this, 'get_settings' ),
            'permission_callback' => '__return_true',
        ) );
    }

    /**
     * Send message endpoint callback
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response|WP_Error
     */
    public function send_message( $request ) {
        // Get request data
        $email = $request->get_param( 'email' );
        $message = $request->get_param( 'message' );
        $recaptcha_token = $request->get_param( 'recaptcha_token' );
        $consent = $request->get_param( 'consent' );

        // Validate consent
        if ( ! $consent || $consent !== '1' ) {
            return new WP_Error(
                'consent_required',
                __( 'Musisz wyrazić zgodę na przetwarzanie danych osobowych.', 'ihumbak-chat' ),
                array( 'status' => 400 )
            );
        }

        // Validate email
        $email = Ihumbak_Security::validate_email( $email );
        if ( false === $email ) {
            return new WP_Error(
                'invalid_email',
                __( 'Please provide a valid email address.', 'ihumbak-chat' ),
                array( 'status' => 400 )
            );
        }

        // Validate message
        $message = Ihumbak_Security::sanitize_message( $message );
        if ( empty( $message ) || strlen( $message ) < 5 ) {
            return new WP_Error(
                'invalid_message',
                __( 'Message must be at least 5 characters long.', 'ihumbak-chat' ),
                array( 'status' => 400 )
            );
        }

        // Check rate limit
        $client_ip = Ihumbak_Security::get_client_ip();
        if ( ! Ihumbak_Security::check_rate_limit( $client_ip ) ) {
            return new WP_Error(
                'rate_limit_exceeded',
                __( 'Too many messages. Please try again later.', 'ihumbak-chat' ),
                array( 'status' => 429 )
            );
        }

        // Verify reCAPTCHA
        $settings = Ihumbak_Settings::get_instance();
        $recaptcha_enabled = ! empty( $settings->get_option( 'ihumbak_recaptcha_site_key', '' ) );
        
        if ( $recaptcha_enabled ) {
            $recaptcha_result = Ihumbak_Security::verify_recaptcha( $recaptcha_token );
            if ( false === $recaptcha_result ) {
                return new WP_Error(
                    'recaptcha_failed',
                    __( 'reCAPTCHA verification failed. Please try again.', 'ihumbak-chat' ),
                    array( 'status' => 400 )
                );
            }
        }

        // Create message
        $post_id = Ihumbak_Messages::create_message( array(
            'email'   => $email,
            'message' => $message,
            'consent' => true,
        ) );

        if ( is_wp_error( $post_id ) ) {
            return new WP_Error(
                'create_failed',
                __( 'Failed to save message. Please try again.', 'ihumbak-chat' ),
                array( 'status' => 500 )
            );
        }

        // Send email notification
        if ( $settings->get_option( 'ihumbak_enable_notifications', 1 ) ) {
            Ihumbak_Email::send_admin_notification( array(
                'email'   => $email,
                'message' => $message,
                'post_id' => $post_id,
            ) );
        }

        return rest_ensure_response( array(
            'success' => true,
            'message' => __( 'Message sent successfully!', 'ihumbak-chat' ),
        ) );
    }

    /**
     * Get settings endpoint callback
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response
     */
    public function get_settings( $request ) {
        $settings = Ihumbak_Settings::get_instance();

        return rest_ensure_response( array(
            'recaptchaSiteKey'    => $settings->get_option( 'ihumbak_recaptcha_site_key', '' ),
            'widgetColor'         => $settings->get_option( 'ihumbak_widget_color', '#007bff' ),
            'widgetPosition'      => $settings->get_option( 'ihumbak_widget_position', 'bottom-right' ),
            'widgetTitle'         => $settings->get_option( 'ihumbak_widget_title', 'Get in Touch' ),
            'introText'           => $settings->get_option( 'ihumbak_intro_text', '' ),
            'emailLabel'          => $settings->get_option( 'ihumbak_email_label', 'Your Email' ),
            'emailPlaceholder'    => $settings->get_option( 'ihumbak_email_placeholder', 'your@email.com' ),
            'emailErrorMessage'   => $settings->get_option( 'ihumbak_email_error_message', 'Please enter a valid email address.' ),
            'messageErrorMessage' => $settings->get_option( 'ihumbak_message_error_message', 'Message must be at least 5 characters.' ),
            'consentErrorMessage' => $settings->get_option( 'ihumbak_consent_error_message', 'You must consent to data processing.' ),
            'consentText'         => $settings->get_option( 'ihumbak_consent_text', 'Wyrażam zgodę na przetwarzanie moich danych osobowych w celu udzielenia odpowiedzi na przesłaną wiadomość.' ),
            'headerFontColor'     => $settings->get_option( 'ihumbak_header_font_color', '#ffffff' ),
            'messageLabel'        => $settings->get_option( 'ihumbak_message_label', 'Message' ),
            'messagePlaceholder'  => $settings->get_option( 'ihumbak_message_placeholder', 'Your message...' ),
            'submitButtonText'    => $settings->get_option( 'ihumbak_submit_button_text', 'Send' ),
        ) );
    }
}
