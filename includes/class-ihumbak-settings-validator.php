<?php
/**
 * Settings Validation and Saving
 *
 * @package Ihumbak_Chat
 */

class Ihumbak_Settings_Validator {

    /**
     * Constructor
     */
    public function __construct() {
    }

    /**
     * Validate and save all settings
     *
     * @param array $data Settings data.
     * @return bool|WP_Error
     */
    public function save_all( $data ) {
        // Validate email
        if ( isset( $data['ihumbak_admin_email'] ) ) {
            $email = Ihumbak_Security::validate_email( $data['ihumbak_admin_email'] );
            if ( false === $email ) {
                return new WP_Error( 'invalid_email', __( 'Invalid email address.', 'ihumbak-chat' ) );
            }
            update_option( 'ihumbak_admin_email', $email );
        }

        // Save reCAPTCHA keys
        if ( isset( $data['ihumbak_recaptcha_site_key'] ) ) {
            update_option( 'ihumbak_recaptcha_site_key', sanitize_text_field( $data['ihumbak_recaptcha_site_key'] ) );
        }
        if ( isset( $data['ihumbak_recaptcha_secret_key'] ) ) {
            update_option( 'ihumbak_recaptcha_secret_key', sanitize_text_field( $data['ihumbak_recaptcha_secret_key'] ) );
        }

        // Save spam limit
        if ( isset( $data['ihumbak_spam_limit'] ) ) {
            $limit = absint( $data['ihumbak_spam_limit'] );
            if ( $limit < 1 ) {
                $limit = 5;
            }
            update_option( 'ihumbak_spam_limit', $limit );
        }

        // Save notifications
        if ( isset( $data['ihumbak_enable_notifications'] ) ) {
            update_option( 'ihumbak_enable_notifications', absint( $data['ihumbak_enable_notifications'] ) );
        }

        // Save widget settings
        $this->save_widget_settings( $data );

        // Save field labels and placeholders
        $this->save_field_settings( $data );

        // Save email templates
        $this->save_email_template_settings( $data );

        // Save widget admin only
        if ( isset( $data['ihumbak_widget_admin_only'] ) ) {
            update_option( 'ihumbak_widget_admin_only', absint( $data['ihumbak_widget_admin_only'] ) );
        } else {
            update_option( 'ihumbak_widget_admin_only', 0 );
        }

        // Save disable admin reply
        if ( isset( $data['ihumbak_disable_admin_reply'] ) ) {
            update_option( 'ihumbak_disable_admin_reply', absint( $data['ihumbak_disable_admin_reply'] ) );
        } else {
            update_option( 'ihumbak_disable_admin_reply', 0 );
        }

        return true;
    }

    /**
     * Save widget-related settings
     *
     * @param array $data Settings data.
     */
    private function save_widget_settings( $data ) {
        // Save widget position
        if ( isset( $data['ihumbak_widget_position'] ) ) {
            $position = sanitize_text_field( $data['ihumbak_widget_position'] );
            $valid_positions = array( 'bottom-right', 'bottom-left', 'top-right', 'top-left' );
            if ( in_array( $position, $valid_positions, true ) ) {
                update_option( 'ihumbak_widget_position', $position );
            }
        }

        // Save widget color
        if ( isset( $data['ihumbak_widget_color'] ) ) {
            $color = sanitize_hex_color( $data['ihumbak_widget_color'] );
            if ( $color ) {
                update_option( 'ihumbak_widget_color', $color );
            }
        }

        // Save widget title
        if ( isset( $data['ihumbak_widget_title'] ) ) {
            update_option( 'ihumbak_widget_title', sanitize_text_field( $data['ihumbak_widget_title'] ) );
        }

        // Save intro text
        if ( isset( $data['ihumbak_intro_text'] ) ) {
            update_option( 'ihumbak_intro_text', wp_kses_post( $data['ihumbak_intro_text'] ) );
        } else {
            update_option( 'ihumbak_intro_text', '' );
        }

        // Save header font color
        if ( isset( $data['ihumbak_header_font_color'] ) ) {
            update_option( 'ihumbak_header_font_color', sanitize_hex_color( $data['ihumbak_header_font_color'] ) );
        }
    }

    /**
     * Save field labels, placeholders and error messages
     *
     * @param array $data Settings data.
     */
    private function save_field_settings( $data ) {
        // Email field
        if ( isset( $data['ihumbak_email_label'] ) ) {
            update_option( 'ihumbak_email_label', sanitize_text_field( $data['ihumbak_email_label'] ) );
        }
        if ( isset( $data['ihumbak_email_placeholder'] ) ) {
            update_option( 'ihumbak_email_placeholder', sanitize_text_field( $data['ihumbak_email_placeholder'] ) );
        }
        if ( isset( $data['ihumbak_email_error_message'] ) ) {
            update_option( 'ihumbak_email_error_message', sanitize_text_field( $data['ihumbak_email_error_message'] ) );
        }

        // Message field
        if ( isset( $data['ihumbak_message_label'] ) ) {
            update_option( 'ihumbak_message_label', sanitize_text_field( $data['ihumbak_message_label'] ) );
        }
        if ( isset( $data['ihumbak_message_placeholder'] ) ) {
            update_option( 'ihumbak_message_placeholder', sanitize_text_field( $data['ihumbak_message_placeholder'] ) );
        }
        if ( isset( $data['ihumbak_message_error_message'] ) ) {
            update_option( 'ihumbak_message_error_message', sanitize_text_field( $data['ihumbak_message_error_message'] ) );
        }

        // Consent
        if ( isset( $data['ihumbak_consent_text'] ) ) {
            update_option( 'ihumbak_consent_text', sanitize_textarea_field( $data['ihumbak_consent_text'] ) );
        }
        if ( isset( $data['ihumbak_consent_error_message'] ) ) {
            update_option( 'ihumbak_consent_error_message', sanitize_text_field( $data['ihumbak_consent_error_message'] ) );
        }

        // Submit button
        if ( isset( $data['ihumbak_submit_button_text'] ) ) {
            update_option( 'ihumbak_submit_button_text', sanitize_text_field( $data['ihumbak_submit_button_text'] ) );
        }
    }

    /**
     * Save email template settings
     *
     * @param array $data Settings data.
     */
    private function save_email_template_settings( $data ) {
        // Admin notification email
        if ( isset( $data['ihumbak_email_subject'] ) ) {
            update_option( 'ihumbak_email_subject', sanitize_text_field( $data['ihumbak_email_subject'] ) );
        }
        if ( isset( $data['ihumbak_email_header'] ) ) {
            update_option( 'ihumbak_email_header', wp_kses_post( $data['ihumbak_email_header'] ) );
        } else {
            update_option( 'ihumbak_email_header', '' );
        }
        if ( isset( $data['ihumbak_email_footer'] ) ) {
            update_option( 'ihumbak_email_footer', wp_kses_post( $data['ihumbak_email_footer'] ) );
        } else {
            update_option( 'ihumbak_email_footer', '' );
        }

        // Hide admin link option
        if ( isset( $data['ihumbak_hide_admin_link'] ) ) {
            update_option( 'ihumbak_hide_admin_link', absint( $data['ihumbak_hide_admin_link'] ) );
        } else {
            update_option( 'ihumbak_hide_admin_link', 0 );
        }

        // Reply email
        if ( isset( $data['ihumbak_reply_email_subject'] ) ) {
            update_option( 'ihumbak_reply_email_subject', sanitize_text_field( $data['ihumbak_reply_email_subject'] ) );
        }
        if ( isset( $data['ihumbak_reply_email_header'] ) ) {
            update_option( 'ihumbak_reply_email_header', wp_kses_post( $data['ihumbak_reply_email_header'] ) );
        } else {
            update_option( 'ihumbak_reply_email_header', '' );
        }
        if ( isset( $data['ihumbak_reply_email_footer'] ) ) {
            update_option( 'ihumbak_reply_email_footer', wp_kses_post( $data['ihumbak_reply_email_footer'] ) );
        } else {
            update_option( 'ihumbak_reply_email_footer', '' );
        }
    }
}
