<?php
/**
 * Security and Validation Class
 *
 * @package Ihumbak_Chat
 */

class Ihumbak_Security {

    /**
     * Validate email address
     *
     * @param string $email Email address to validate.
     * @return bool|string Returns email if valid, false otherwise.
     */
    public static function validate_email( $email ) {
        $sanitized = sanitize_email( $email );
        if ( is_email( $sanitized ) ) {
            return $sanitized;
        }
        return false;
    }

    /**
     * Sanitize message content
     *
     * @param string $message Message content to sanitize.
     * @return string Sanitized message.
     */
    public static function sanitize_message( $message ) {
        return sanitize_textarea_field( $message );
    }

    /**
     * Verify Google reCAPTCHA v3 token
     *
     * @param string $token reCAPTCHA token.
     * @return bool|array Returns array with score on success, false on failure.
     */
    public static function verify_recaptcha( $token ) {
        $settings = Ihumbak_Settings::get_instance();
        $secret_key = $settings->get_option( 'ihumbak_recaptcha_secret_key', '' );

        if ( empty( $secret_key ) || empty( $token ) ) {
            return false;
        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret'   => $secret_key,
            'response' => $token,
            'remoteip' => self::get_client_ip(),
        );

        $response = wp_remote_post( $url, array(
            'timeout' => 5,
            'body'    => $data,
        ) );

        if ( is_wp_error( $response ) ) {
            return false;
        }

        $body = wp_remote_retrieve_body( $response );
        $result = json_decode( $body, true );

        if ( isset( $result['success'] ) && $result['success'] === true ) {
            $score = isset( $result['score'] ) ? $result['score'] : 0;
            // Accept score >= 0.5
            if ( $score >= 0.5 ) {
                return array( 'success' => true, 'score' => $score );
            }
        }

        return false;
    }

    /**
     * Check rate limit for IP address
     *
     * @param string $ip IP address to check.
     * @return bool True if within limit, false if exceeded.
     */
    public static function check_rate_limit( $ip ) {
        $settings = Ihumbak_Settings::get_instance();
        $limit = $settings->get_option( 'ihumbak_spam_limit', 5 );
        
        $transient_key = 'ihumbak_rate_' . md5( $ip );
        $attempts = get_transient( $transient_key );

        if ( false === $attempts ) {
            // First attempt
            set_transient( $transient_key, 1, HOUR_IN_SECONDS );
            return true;
        }

        if ( $attempts >= $limit ) {
            return false;
        }

        // Increment attempts
        set_transient( $transient_key, $attempts + 1, HOUR_IN_SECONDS );
        return true;
    }

    /**
     * Get client IP address
     *
     * @return string Client IP address.
     */
    public static function get_client_ip() {
        $ip = '';

        if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            $ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
        } elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            $ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
        } elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
            $ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
        }

        return $ip;
    }

    /**
     * Get user agent
     *
     * @return string User agent.
     */
    public static function get_user_agent() {
        return isset( $_SERVER['HTTP_USER_AGENT'] ) 
            ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) 
            : '';
    }

    /**
     * Get referer URL
     *
     * @return string Referer URL.
     */
    public static function get_referer() {
        return isset( $_SERVER['HTTP_REFERER'] ) 
            ? esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) 
            : '';
    }
}
