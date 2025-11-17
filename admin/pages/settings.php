<?php
/**
 * Settings Admin Page
 *
 * @package Ihumbak_Chat
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$settings = Ihumbak_Settings::get_instance();
$options = $settings->get_all_options();

// Get active tab
$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'general';
?>

<div class="wrap">
    <h1><?php esc_html_e( 'iHumbak Chat Settings', 'ihumbak-chat' ); ?></h1>

    <?php settings_errors( 'ihumbak_settings' ); ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=ihumbak-settings&tab=general" class="nav-tab <?php echo 'general' === $active_tab ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'General', 'ihumbak-chat' ); ?>
        </a>
        <a href="?page=ihumbak-settings&tab=appearance" class="nav-tab <?php echo 'appearance' === $active_tab ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Appearance', 'ihumbak-chat' ); ?>
        </a>
        <a href="?page=ihumbak-settings&tab=email-template" class="nav-tab <?php echo 'email-template' === $active_tab ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Email Template', 'ihumbak-chat' ); ?>
        </a>
        <a href="?page=ihumbak-settings&tab=advanced" class="nav-tab <?php echo 'advanced' === $active_tab ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Advanced', 'ihumbak-chat' ); ?>
        </a>
    </h2>

    <form method="post" action="">
        <?php wp_nonce_field( 'ihumbak_settings', '_wpnonce' ); ?>

        <?php if ( 'general' === $active_tab ) : ?>
            <?php include IHUMBAK_CHAT_PLUGIN_DIR . 'admin/views/settings-general.php'; ?>

        <?php elseif ( 'appearance' === $active_tab ) : ?>
            <?php include IHUMBAK_CHAT_PLUGIN_DIR . 'admin/views/settings-appearance.php'; ?>

        <?php elseif ( 'email-template' === $active_tab ) : ?>
            <?php include IHUMBAK_CHAT_PLUGIN_DIR . 'admin/views/settings-email-template.php'; ?>

        <?php elseif ( 'advanced' === $active_tab ) : ?>
            <?php include IHUMBAK_CHAT_PLUGIN_DIR . 'admin/views/settings-advanced.php'; ?>

        <?php endif; ?>

        <p class="submit">
            <button type="submit" name="ihumbak_settings_submit" class="button button-primary">
                <?php esc_html_e( 'Save Settings', 'ihumbak-chat' ); ?>
            </button>
        </p>
    </form>
</div>
