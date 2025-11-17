<?php
/**
 * Advanced Settings Tab View
 *
 * @package Ihumbak_Chat
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<!-- ADVANCED TAB -->
<table class="form-table">
    <tbody>
        <tr>
            <th scope="row">
                <?php esc_html_e( 'Widget Visibility', 'ihumbak-chat' ); ?>
            </th>
            <td>
                <label>
                    <input type="checkbox" 
                           name="ihumbak_widget_admin_only" 
                           value="1" 
                           <?php checked( $options['ihumbak_widget_admin_only'], 1 ); ?>>
                    <?php esc_html_e( 'Show widget only for administrators (for testing)', 'ihumbak-chat' ); ?>
                </label>
                <p class="description">
                    <?php esc_html_e( 'When enabled, the chat widget will only be visible to logged-in administrators.', 'ihumbak-chat' ); ?>
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <?php esc_html_e( 'Admin Reply', 'ihumbak-chat' ); ?>
            </th>
            <td>
                <label>
                    <input type="checkbox" 
                           name="ihumbak_disable_admin_reply" 
                           value="1" 
                           <?php checked( $options['ihumbak_disable_admin_reply'], 1 ); ?>>
                    <?php esc_html_e( 'Disable direct reply from admin panel', 'ihumbak-chat' ); ?>
                </label>
                <p class="description">
                    <?php esc_html_e( 'When enabled, administrators will not be able to send replies directly from the message view page.', 'ihumbak-chat' ); ?>
                </p>
            </td>
        </tr>
    </tbody>
</table>
