<?php
/**
 * Email Notifications Class
 *
 * @package Ihumbak_Chat
 */

class Ihumbak_Email {

    /**
     * Send admin notification
     *
     * @param array $data Message data.
     * @return bool
     */
    public static function send_admin_notification( $data ) {
        $settings = Ihumbak_Settings::get_instance();
        $admin_email = $settings->get_option( 'ihumbak_admin_email', get_option( 'admin_email' ) );

        if ( empty( $admin_email ) ) {
            return false;
        }

        $email = isset( $data['email'] ) ? $data['email'] : '';
        $message = isset( $data['message'] ) ? $data['message'] : '';
        $post_id = isset( $data['post_id'] ) ? $data['post_id'] : 0;

        $email_subject_template = $settings->get_option( 'ihumbak_email_subject', '[{site}] New message from {email}' );
        $subject = str_replace(
            array( '{site}', '{email}' ),
            array( get_bloginfo( 'name' ), $email ),
            $email_subject_template
        );

        $body = self::get_email_template( array(
            'email'   => $email,
            'message' => $message,
            'post_id' => $post_id,
        ) );

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>',
            'Reply-To: ' . $email,
        );

        $sent = wp_mail( $admin_email, $subject, $body, $headers );

        do_action( 'ihumbak_email_sent', $sent, $data );

        return $sent;
    }

    /**
     * Get email template
     *
     * @param array $data Template data.
     * @return string
     */
    private static function get_email_template( $data ) {
        $email = isset( $data['email'] ) ? $data['email'] : '';
        $message = isset( $data['message'] ) ? $data['message'] : '';
        $post_id = isset( $data['post_id'] ) ? $data['post_id'] : 0;

        $view_url = admin_url( 'admin.php?page=ihumbak-messages&action=view&id=' . $post_id );

        $settings = Ihumbak_Settings::get_instance();
        $email_header = $settings->get_option( 'ihumbak_email_header', '' );
        $email_footer = $settings->get_option( 'ihumbak_email_footer', '' );
        $email_intro_text = $settings->get_option( 'ihumbak_email_intro_text', 'New message received from iHumbak Chat widget:' );
        $hide_admin_link = $settings->get_option( 'ihumbak_hide_admin_link', 0 );

        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                .info {
                    margin: 10px 0;
                    font-size: 14px;
                }
                .message-content {
                    background-color: #f9f9f9;
                    padding: 15px;
                    border-left: 4px solid #007bff;
                    margin: 15px 0;
                    border-radius: 3px;
                }
                .link {
                    color: #007bff;
                    text-decoration: underline;
                }
                .email-header {
                    margin-bottom: 20px;
                    padding-bottom: 15px;
                    border-bottom: 1px solid #ddd;
                    white-space: pre-wrap;
                    word-wrap: break-word;
                }
                .email-footer {
                    margin-top: 20px;
                    padding-top: 15px;
                    border-top: 1px solid #ddd;
                    font-size: 12px;
                    color: #666;
                    white-space: pre-wrap;
                    word-wrap: break-word;
                }
                .footer {
                    margin-top: 20px;
                    padding-top: 15px;
                    border-top: 1px solid #ddd;
                    font-size: 12px;
                    color: #666;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <?php if ( ! empty( $email_header ) ) : ?>
                    <div class="email-header">
                        <?php echo wp_kses_post( $email_header ); ?>
                    </div>
                <?php endif; ?>

                <div class="info">
                    <strong><?php esc_html_e( 'Email:', 'ihumbak-chat' ); ?></strong> <?php echo esc_html( $email ); ?>
                </div>
                <div class="info">
                    <strong><?php esc_html_e( 'Date:', 'ihumbak-chat' ); ?></strong> <?php echo esc_html( current_time( 'Y-m-d H:i:s' ) ); ?>
                </div>

                <div class="message-content">
                    <?php echo nl2br( esc_html( $message ) ); ?>
                </div>

                <?php if ( ! $hide_admin_link ) : ?>
                <p>
                    <a href="<?php echo esc_url( $view_url ); ?>" class="link"><?php esc_html_e( 'View in admin panel', 'ihumbak-chat' ); ?></a>
                </p>
                <?php endif; ?>

                <?php if ( ! empty( $email_footer ) ) : ?>
                    <div class="email-footer">
                        <?php echo wp_kses_post( $email_footer ); ?>
                    </div>
                <?php endif; ?>

                <div class="footer">
                    <p><?php echo esc_html( sprintf( __( 'Sent from %s', 'ihumbak-chat' ), get_bloginfo( 'name' ) ) ); ?></p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Send reply to customer
     *
     * @param int    $message_id Message post ID.
     * @param string $reply_text Reply text/HTML.
     * @return bool
     */
    public static function send_reply( $message_id, $reply_text ) {
        $post = get_post( $message_id );

        if ( ! $post || 'ihumbak_message' !== get_post_type( $post ) ) {
            return false;
        }

        $customer_email = get_post_meta( $message_id, 'ihumbak_email', true );

        if ( empty( $customer_email ) ) {
            return false;
        }

        $settings = Ihumbak_Settings::get_instance();
        $reply_subject_template = $settings->get_option( 'ihumbak_reply_email_subject', 'Re: Your message from {site}' );
        $subject = str_replace(
            '{site}',
            get_bloginfo( 'name' ),
            $reply_subject_template
        );

        $body = self::get_reply_template( array(
            'reply'   => $reply_text,
            'site_name' => get_bloginfo( 'name' ),
            'original_message' => $post->post_content,
        ) );

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>',
        );

        $sent = wp_mail( $customer_email, $subject, $body, $headers );

        if ( $sent ) {
            // Save reply to post meta
            update_post_meta( $message_id, 'ihumbak_admin_reply', $reply_text );
            update_post_meta( $message_id, 'ihumbak_reply_date', current_time( 'Y-m-d H:i:s' ) );
            update_post_meta( $message_id, 'ihumbak_reply_sent_at', current_time( 'timestamp' ) );
            update_post_meta( $message_id, 'ihumbak_replied_by', get_current_user_id() );
        }

        do_action( 'ihumbak_reply_sent', $sent, $message_id, $reply_text );

        return $sent;
    }

    /**
     * Get reply email template
     *
     * @param array $data Template data.
     * @return string
     */
    private static function get_reply_template( $data ) {
        $reply = isset( $data['reply'] ) ? $data['reply'] : '';
        $site_name = isset( $data['site_name'] ) ? $data['site_name'] : '';
        $original_message = isset( $data['original_message'] ) ? $data['original_message'] : '';

        $settings = Ihumbak_Settings::get_instance();
        $reply_header = $settings->get_option( 'ihumbak_reply_email_header', '' );
        $reply_footer = $settings->get_option( 'ihumbak_reply_email_footer', '' );

        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                .email-header {
                    margin-bottom: 20px;
                    padding-bottom: 15px;
                    border-bottom: 1px solid #ddd;
                    white-space: pre-wrap;
                    word-wrap: break-word;
                }
                .reply-content {
                    background-color: #f9f9f9;
                    padding: 15px;
                    border-left: 4px solid #007bff;
                    margin: 15px 0;
                    border-radius: 3px;
                }
                .original-message {
                    background-color: #f0f0f0;
                    padding: 12px;
                    border-left: 4px solid #ccc;
                    margin: 20px 0 15px 0;
                    border-radius: 3px;
                    font-size: 13px;
                    color: #666;
                }
                .message-label {
                    font-weight: bold;
                    color: #333;
                    margin-bottom: 8px;
                    font-size: 12px;
                    text-transform: uppercase;
                }
                .email-footer {
                    margin-top: 20px;
                    padding-top: 15px;
                    border-top: 1px solid #ddd;
                    font-size: 12px;
                    color: #666;
                    white-space: pre-wrap;
                    word-wrap: break-word;
                }
                .footer {
                    margin-top: 20px;
                    padding-top: 15px;
                    border-top: 1px solid #ddd;
                    font-size: 12px;
                    color: #666;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <?php if ( ! empty( $reply_header ) ) : ?>
                    <div class="email-header">
                        <?php echo wp_kses_post( $reply_header ); ?>
                    </div>
                <?php endif; ?>

                <div class="reply-content">
                    <?php echo wp_kses_post( $reply ); ?>
                </div>

                <?php if ( ! empty( $original_message ) ) : ?>
                    <div class="original-message">
                        <div class="message-label"><?php esc_html_e( 'Your original message:', 'ihumbak-chat' ); ?></div>
                        <?php echo nl2br( esc_html( $original_message ) ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $reply_footer ) ) : ?>
                    <div class="email-footer">
                        <?php echo wp_kses_post( $reply_footer ); ?>
                    </div>
                <?php endif; ?>

                <div class="footer">
                    <p><?php echo esc_html( sprintf( __( 'From %s', 'ihumbak-chat' ), $site_name ) ); ?></p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
}
