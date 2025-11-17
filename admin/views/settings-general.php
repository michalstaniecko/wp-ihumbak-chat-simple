<?php
/**
 * General Settings Tab View
 *
 * @package Ihumbak_Chat
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<!-- GENERAL TAB -->
<table class="form-table">
    <tbody>
        <tr>
            <th scope="row">
                <label for="ihumbak_admin_email"><?php esc_html_e( 'Admin Email', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <input type="email" 
                       id="ihumbak_admin_email" 
                       name="ihumbak_admin_email" 
                       value="<?php echo esc_attr( $options['ihumbak_admin_email'] ); ?>" 
                       class="regular-text">
                <p class="description">
                    <?php esc_html_e( 'Email address where notifications will be sent.', 'ihumbak-chat' ); ?>
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_recaptcha_site_key"><?php esc_html_e( 'reCAPTCHA Site Key', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <input type="text" 
                       id="ihumbak_recaptcha_site_key" 
                       name="ihumbak_recaptcha_site_key" 
                       value="<?php echo esc_attr( $options['ihumbak_recaptcha_site_key'] ); ?>" 
                       class="regular-text">
                <p class="description">
                    <?php esc_html_e( 'Get keys from Google reCAPTCHA Admin Console.', 'ihumbak-chat' ); ?>
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_recaptcha_secret_key"><?php esc_html_e( 'reCAPTCHA Secret Key', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <input type="password" 
                       id="ihumbak_recaptcha_secret_key" 
                       name="ihumbak_recaptcha_secret_key" 
                       value="<?php echo esc_attr( $options['ihumbak_recaptcha_secret_key'] ); ?>" 
                       class="regular-text">
                <p class="description">
                    <?php esc_html_e( 'Keep this key secret and never share it.', 'ihumbak-chat' ); ?>
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_spam_limit"><?php esc_html_e( 'Spam Limit (per hour)', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <input type="number" 
                       id="ihumbak_spam_limit" 
                       name="ihumbak_spam_limit" 
                       value="<?php echo absint( $options['ihumbak_spam_limit'] ); ?>" 
                       min="1" 
                       class="small-text">
                <p class="description">
                    <?php esc_html_e( 'Maximum number of messages per hour from one IP address.', 'ihumbak-chat' ); ?>
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_enable_notifications"><?php esc_html_e( 'Enable Notifications', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <label>
                    <input type="checkbox" 
                           id="ihumbak_enable_notifications" 
                           name="ihumbak_enable_notifications" 
                           value="1" 
                           <?php checked( $options['ihumbak_enable_notifications'], 1 ); ?>>
                    <?php esc_html_e( 'Send email notifications for new messages', 'ihumbak-chat' ); ?>
                </label>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_consent_text"><?php esc_html_e( 'Consent Text', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <textarea id="ihumbak_consent_text" 
                          name="ihumbak_consent_text" 
                          rows="3" 
                          class="large-text"><?php echo esc_textarea( $options['ihumbak_consent_text'] ); ?></textarea>
                <p class="description">
                    <?php esc_html_e( 'Text for the consent checkbox.', 'ihumbak-chat' ); ?>
                </p>
            </td>
        </tr>
    </tbody>
</table>
