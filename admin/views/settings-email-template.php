<?php
/**
 * Email Template Settings Tab View
 *
 * @package Ihumbak_Chat
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<!-- EMAIL TEMPLATE TAB -->

<h3><?php esc_html_e( 'Admin Notification Email', 'ihumbak-chat' ); ?></h3>
<p class="description"><?php esc_html_e( 'Settings for emails sent to admin when new messages are received.', 'ihumbak-chat' ); ?></p>

<table class="form-table">
    <tbody>
        <tr>
            <th scope="row">
                <label for="ihumbak_email_subject"><?php esc_html_e( 'Email Subject', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <input type="text" 
                       id="ihumbak_email_subject" 
                       name="ihumbak_email_subject" 
                       value="<?php echo esc_attr( $options['ihumbak_email_subject'] ); ?>" 
                       class="regular-text">
                <p class="description">
                    <?php esc_html_e( 'Subject line. Use {email} for customer email, {site} for site name.', 'ihumbak-chat' ); ?>
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_email_header"><?php esc_html_e( 'Email Header', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <?php
                wp_editor(
                    $options['ihumbak_email_header'],
                    'ihumbak_email_header',
                    array(
                        'media_buttons' => false,
                        'textarea_rows' => 6,
                        'teeny'         => true,
                        'quicktags'     => true,
                    )
                );
                ?>
                <p class="description">
                    <?php esc_html_e( 'Optional HTML at top of email.', 'ihumbak-chat' ); ?>
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_email_footer"><?php esc_html_e( 'Email Footer', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <?php
                wp_editor(
                    $options['ihumbak_email_footer'],
                    'ihumbak_email_footer',
                    array(
                        'media_buttons' => false,
                        'textarea_rows' => 6,
                        'teeny'         => true,
                        'quicktags'     => true,
                    )
                );
                ?>
                <p class="description">
                    <?php esc_html_e( 'Optional HTML at bottom of email.', 'ihumbak-chat' ); ?>
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_hide_admin_link"><?php esc_html_e( 'Hide Admin Panel Link', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <label>
                    <input type="checkbox" 
                           id="ihumbak_hide_admin_link" 
                           name="ihumbak_hide_admin_link" 
                           value="1"
                           <?php checked( 1, $options['ihumbak_hide_admin_link'] ); ?>>
                    <?php esc_html_e( 'Hide "View in admin panel" link in notification emails', 'ihumbak-chat' ); ?>
                </label>
                <p class="description">
                    <?php esc_html_e( 'Check this to remove the link to the message in the admin panel from notification emails.', 'ihumbak-chat' ); ?>
                </p>
            </td>
        </tr>
    </tbody>
</table>

<hr style="margin: 40px 0;">

<h3><?php esc_html_e( 'Customer Reply Email', 'ihumbak-chat' ); ?></h3>
<p class="description"><?php esc_html_e( 'Settings for emails sent to customers with admin replies.', 'ihumbak-chat' ); ?></p>

<table class="form-table">
    <tbody>
        <tr>
            <th scope="row">
                <label for="ihumbak_reply_email_subject"><?php esc_html_e( 'Email Subject', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <input type="text" 
                       id="ihumbak_reply_email_subject" 
                       name="ihumbak_reply_email_subject" 
                       value="<?php echo esc_attr( $options['ihumbak_reply_email_subject'] ); ?>" 
                       class="regular-text">
                <p class="description">
                    <?php esc_html_e( 'Subject line. Use {site} for site name.', 'ihumbak-chat' ); ?>
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_reply_email_header"><?php esc_html_e( 'Email Header', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <?php
                wp_editor(
                    $options['ihumbak_reply_email_header'],
                    'ihumbak_reply_email_header',
                    array(
                        'media_buttons' => false,
                        'textarea_rows' => 6,
                        'teeny'         => true,
                        'quicktags'     => true,
                    )
                );
                ?>
                <p class="description">
                    <?php esc_html_e( 'Optional HTML at top of email.', 'ihumbak-chat' ); ?>
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_reply_email_footer"><?php esc_html_e( 'Email Footer', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <?php
                wp_editor(
                    $options['ihumbak_reply_email_footer'],
                    'ihumbak_reply_email_footer',
                    array(
                        'media_buttons' => false,
                        'textarea_rows' => 6,
                        'teeny'         => true,
                        'quicktags'     => true,
                    )
                );
                ?>
                <p class="description">
                    <?php esc_html_e( 'Optional HTML at bottom of email.', 'ihumbak-chat' ); ?>
                </p>
            </td>
        </tr>
    </tbody>
</table>
