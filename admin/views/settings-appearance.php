<?php
/**
 * Appearance Settings Tab View
 *
 * @package Ihumbak_Chat
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<!-- APPEARANCE TAB -->
<table class="form-table">
    <tbody>
        <tr>
            <th scope="row">
                <label for="ihumbak_widget_position"><?php esc_html_e( 'Widget Position', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <select id="ihumbak_widget_position" name="ihumbak_widget_position">
                    <option value="bottom-right" <?php selected( $options['ihumbak_widget_position'], 'bottom-right' ); ?>>
                        <?php esc_html_e( 'Bottom Right', 'ihumbak-chat' ); ?>
                    </option>
                    <option value="bottom-left" <?php selected( $options['ihumbak_widget_position'], 'bottom-left' ); ?>>
                        <?php esc_html_e( 'Bottom Left', 'ihumbak-chat' ); ?>
                    </option>
                    <option value="top-right" <?php selected( $options['ihumbak_widget_position'], 'top-right' ); ?>>
                        <?php esc_html_e( 'Top Right', 'ihumbak-chat' ); ?>
                    </option>
                    <option value="top-left" <?php selected( $options['ihumbak_widget_position'], 'top-left' ); ?>>
                        <?php esc_html_e( 'Top Left', 'ihumbak-chat' ); ?>
                    </option>
                </select>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_widget_color"><?php esc_html_e( 'Widget Primary Color', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <input type="text" 
                       id="ihumbak_widget_color" 
                       name="ihumbak_widget_color" 
                       value="<?php echo esc_attr( $options['ihumbak_widget_color'] ); ?>" 
                       class="color-picker">
                <p class="description">
                    <?php esc_html_e( 'Color for header background, bubble, and submit button.', 'ihumbak-chat' ); ?>
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_header_font_color"><?php esc_html_e( 'Header Font Color', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <input type="text" 
                       id="ihumbak_header_font_color" 
                       name="ihumbak_header_font_color" 
                       value="<?php echo esc_attr( $options['ihumbak_header_font_color'] ); ?>" 
                       class="color-picker">
                <p class="description">
                    <?php esc_html_e( 'Text color in the header.', 'ihumbak-chat' ); ?>
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_widget_title"><?php esc_html_e( 'Widget Title', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <input type="text" 
                       id="ihumbak_widget_title" 
                       name="ihumbak_widget_title" 
                       value="<?php echo esc_attr( $options['ihumbak_widget_title'] ); ?>" 
                       class="regular-text">
                <p class="description">
                    <?php esc_html_e( 'Title displayed in the widget header.', 'ihumbak-chat' ); ?>
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_intro_text"><?php esc_html_e( 'Introduction Text', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <?php
                wp_editor(
                    $options['ihumbak_intro_text'],
                    'ihumbak_intro_text',
                    array(
                        'media_buttons' => false,
                        'textarea_rows' => 4,
                        'teeny'         => true,
                        'quicktags'     => true,
                    )
                );
                ?>
                <p class="description">
                    <?php esc_html_e( 'Optional paragraph below title. Leave empty to hide.', 'ihumbak-chat' ); ?>
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_email_label"><?php esc_html_e( 'Email Field Label', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <input type="text" 
                       id="ihumbak_email_label" 
                       name="ihumbak_email_label" 
                       value="<?php echo esc_attr( $options['ihumbak_email_label'] ); ?>" 
                       class="regular-text">
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_email_placeholder"><?php esc_html_e( 'Email Placeholder', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <input type="text" 
                       id="ihumbak_email_placeholder" 
                       name="ihumbak_email_placeholder" 
                       value="<?php echo esc_attr( $options['ihumbak_email_placeholder'] ); ?>" 
                       class="regular-text">
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_email_error_message"><?php esc_html_e( 'Email Error Message', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <input type="text" 
                       id="ihumbak_email_error_message" 
                       name="ihumbak_email_error_message" 
                       value="<?php echo esc_attr( $options['ihumbak_email_error_message'] ); ?>" 
                       class="regular-text">
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_message_label"><?php esc_html_e( 'Message Field Label', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <input type="text" 
                       id="ihumbak_message_label" 
                       name="ihumbak_message_label" 
                       value="<?php echo esc_attr( $options['ihumbak_message_label'] ); ?>" 
                       class="regular-text">
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_message_placeholder"><?php esc_html_e( 'Message Placeholder', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <input type="text" 
                       id="ihumbak_message_placeholder" 
                       name="ihumbak_message_placeholder" 
                       value="<?php echo esc_attr( $options['ihumbak_message_placeholder'] ); ?>" 
                       class="regular-text">
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_message_error_message"><?php esc_html_e( 'Message Error Message', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <input type="text" 
                       id="ihumbak_message_error_message" 
                       name="ihumbak_message_error_message" 
                       value="<?php echo esc_attr( $options['ihumbak_message_error_message'] ); ?>" 
                       class="regular-text">
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_consent_error_message"><?php esc_html_e( 'Consent Error Message', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <input type="text" 
                       id="ihumbak_consent_error_message" 
                       name="ihumbak_consent_error_message" 
                       value="<?php echo esc_attr( $options['ihumbak_consent_error_message'] ); ?>" 
                       class="regular-text">
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="ihumbak_submit_button_text"><?php esc_html_e( 'Submit Button Text', 'ihumbak-chat' ); ?></label>
            </th>
            <td>
                <input type="text" 
                       id="ihumbak_submit_button_text" 
                       name="ihumbak_submit_button_text" 
                       value="<?php echo esc_attr( $options['ihumbak_submit_button_text'] ); ?>" 
                       class="regular-text">
            </td>
        </tr>
    </tbody>
</table>

<script>
    jQuery(document).ready(function($) {
        $('.color-picker').wpColorPicker();
    });
</script>
