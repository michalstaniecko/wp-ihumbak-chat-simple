<?php
/**
 * Settings Default Values
 *
 * @package Ihumbak_Chat
 */

class Ihumbak_Settings_Defaults {

    /**
     * Get all default settings
     *
     * @return array
     */
    public static function get_all() {
        return array(
            'ihumbak_admin_email'           => '',
            'ihumbak_recaptcha_site_key'    => '',
            'ihumbak_recaptcha_secret_key'  => '',
            'ihumbak_spam_limit'            => 5,
            'ihumbak_enable_notifications'  => 1,
            'ihumbak_widget_position'       => 'bottom-right',
            'ihumbak_widget_color'          => '#007bff',
            'ihumbak_widget_title'          => 'Get in Touch',
            'ihumbak_intro_text'            => '',
            'ihumbak_email_label'           => 'Your Email',
            'ihumbak_email_placeholder'     => 'your@email.com',
            'ihumbak_email_error_message'   => 'Please enter a valid email address.',
            'ihumbak_message_label'         => 'Message',
            'ihumbak_message_placeholder'   => 'Your message...',
            'ihumbak_message_error_message' => 'Message must be at least 5 characters.',
            'ihumbak_consent_error_message' => 'You must consent to data processing.',
            'ihumbak_consent_text'          => 'Wyrażam zgodę na przetwarzanie moich danych osobowych w celu udzielenia odpowiedzi na przesłaną wiadomość.',
            'ihumbak_widget_admin_only'     => 0,
            'ihumbak_disable_admin_reply'   => 0,
            'ihumbak_header_font_color'     => '#ffffff',
            'ihumbak_submit_button_text'    => 'Send',
            'ihumbak_email_header'          => '',
            'ihumbak_email_footer'          => '',
            'ihumbak_email_subject'         => '[{site}] New message from {email}',
            'ihumbak_hide_admin_link'       => 0,
            'ihumbak_reply_email_header'    => '',
            'ihumbak_reply_email_footer'    => '',
            'ihumbak_reply_email_subject'   => 'Re: Your message from {site}',
        );
    }

    /**
     * Get default value for a setting
     *
     * @param string $key Setting key.
     * @return mixed
     */
    public static function get( $key ) {
        $defaults = self::get_all();
        return isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
    }
}
