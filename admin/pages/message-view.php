<?php
/**
 * Message View Admin Page
 *
 * @package Ihumbak_Chat
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$post_id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;

if ( ! $post_id || 'ihumbak_message' !== get_post_type( $post_id ) ) {
    wp_die( esc_html__( 'Invalid message ID.', 'ihumbak-chat' ) );
}

// Handle reply submission
$reply_sent = false;
if ( isset( $_POST['ihumbak_send_reply'] ) && isset( $_POST['ihumbak_reply_nonce'] ) ) {
    // Check if replies are enabled
    $settings_check = Ihumbak_Settings::get_instance();
    $disable_admin_reply_check = $settings_check->get_option( 'ihumbak_disable_admin_reply', 0 );
    
    if ( ! $disable_admin_reply_check && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ihumbak_reply_nonce'] ) ), 'send_reply_' . $post_id ) ) {
        $reply_text = isset( $_POST['ihumbak_reply'] ) ? wp_kses_post( wp_unslash( $_POST['ihumbak_reply'] ) ) : '';
        
        if ( ! empty( $reply_text ) ) {
            if ( Ihumbak_Email::send_reply( $post_id, $reply_text ) ) {
                Ihumbak_Messages::mark_as_responded( $post_id );
                $reply_sent = true;
            }
        }
    }
}

// Mark as read when viewing
$is_read = get_post_meta( $post_id, 'ihumbak_read', true );
if ( ! $is_read ) {
    Ihumbak_Messages::mark_as_read( $post_id );
}

$post = get_post( $post_id );
$email = get_post_meta( $post_id, 'ihumbak_email', true );
$ip = get_post_meta( $post_id, 'ihumbak_ip', true );
$user_agent = get_post_meta( $post_id, 'ihumbak_user_agent', true );
$referer = get_post_meta( $post_id, 'ihumbak_referer', true );
$consent_given = get_post_meta( $post_id, 'ihumbak_consent_given', true );
$is_responded = get_post_meta( $post_id, 'ihumbak_responded', true );

// Get settings
$settings = Ihumbak_Settings::get_instance();
$disable_admin_reply = $settings->get_option( 'ihumbak_disable_admin_reply', 0 );

// Get reply info
$admin_reply = get_post_meta( $post_id, 'ihumbak_admin_reply', true );
$reply_date = get_post_meta( $post_id, 'ihumbak_reply_date', true );
$replied_by_user_id = get_post_meta( $post_id, 'ihumbak_replied_by', true );
$has_reply = ! empty( $admin_reply );
?>

<div class="wrap">
    <h1><?php esc_html_e( 'View Message', 'ihumbak-chat' ); ?></h1>

    <?php if ( isset( $_GET['responded'] ) ) : ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e( 'Message marked as responded.', 'ihumbak-chat' ); ?></p>
        </div>
    <?php endif; ?>

    <?php if ( $reply_sent ) : ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e( 'Reply sent to customer successfully!', 'ihumbak-chat' ); ?></p>
        </div>
    <?php endif; ?>

    <p>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=ihumbak-messages' ) ); ?>" class="button">
            &laquo; <?php esc_html_e( 'Back to Messages', 'ihumbak-chat' ); ?>
        </a>
    </p>

    <div class="ihumbak-message-view">
        <div class="card">
            <h2><?php esc_html_e( 'Message Details', 'ihumbak-chat' ); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th><?php esc_html_e( 'From:', 'ihumbak-chat' ); ?></th>
                    <td>
                        <strong><?php echo esc_html( $email ); ?></strong>
                        <a href="mailto:<?php echo esc_attr( $email ); ?>" class="button button-small" style="margin-left: 10px;">
                            <?php esc_html_e( 'Reply via Email', 'ihumbak-chat' ); ?>
                        </a>
                    </td>
                </tr>
                <tr>
                    <th><?php esc_html_e( 'Date:', 'ihumbak-chat' ); ?></th>
                    <td><?php echo esc_html( get_the_date( '', $post ) . ' ' . get_the_time( '', $post ) ); ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e( 'IP Address:', 'ihumbak-chat' ); ?></th>
                    <td><?php echo esc_html( $ip ); ?></td>
                </tr>
                <?php if ( $referer ) : ?>
                <tr>
                    <th><?php esc_html_e( 'Page URL:', 'ihumbak-chat' ); ?></th>
                    <td><a href="<?php echo esc_url( $referer ); ?>" target="_blank"><?php echo esc_html( $referer ); ?></a></td>
                </tr>
                <?php endif; ?>
                <?php if ( $user_agent ) : ?>
                <tr>
                    <th><?php esc_html_e( 'User Agent:', 'ihumbak-chat' ); ?></th>
                    <td><?php echo esc_html( $user_agent ); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <th><?php esc_html_e( 'Consent Given:', 'ihumbak-chat' ); ?></th>
                    <td>
                        <?php if ( $consent_given ) : ?>
                            <span style="color: green;">✓ <?php esc_html_e( 'Yes - User consented to data processing', 'ihumbak-chat' ); ?></span>
                        <?php else : ?>
                            <span style="color: red;">✗ <?php esc_html_e( 'No consent recorded', 'ihumbak-chat' ); ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th><?php esc_html_e( 'Response Status:', 'ihumbak-chat' ); ?></th>
                    <td>
                        <?php if ( $is_responded ) : ?>
                            <span style="color: green;">✓ <?php esc_html_e( 'Responded', 'ihumbak-chat' ); ?></span>
                        <?php else : ?>
                            <span style="color: orange;">○ <?php esc_html_e( 'Not responded yet', 'ihumbak-chat' ); ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>

        <div class="card" style="margin-top: 20px;">
            <h2><?php esc_html_e( 'Message Content', 'ihumbak-chat' ); ?></h2>
            <div style="background: #f9f9f9; padding: 15px; border-left: 4px solid #007bff;">
                <?php echo nl2br( esc_html( $post->post_content ) ); ?>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <?php if ( ! $is_responded ) : ?>
                <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=ihumbak-messages&action=mark_responded&id=' . $post_id ), 'mark_responded_' . $post_id ) ); ?>" 
                   class="button button-primary">
                    <?php esc_html_e( 'Mark as Responded', 'ihumbak-chat' ); ?>
                </a>
            <?php endif; ?>
            
            <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=ihumbak-messages&action=delete&id=' . $post_id ), 'delete_message_' . $post_id ) ); ?>" 
               class="button button-link-delete" 
               onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to delete this message?', 'ihumbak-chat' ); ?>');">
                <?php esc_html_e( 'Delete Message', 'ihumbak-chat' ); ?>
            </a>
        </div>

        <?php if ( $has_reply ) : ?>
            <div class="card" style="margin-top: 30px; background-color: #f8fff9; border-left: 4px solid #28a745;">
                <h2><?php esc_html_e( 'Reply Sent to Customer', 'ihumbak-chat' ); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th><?php esc_html_e( 'Sent:', 'ihumbak-chat' ); ?></th>
                        <td><?php echo esc_html( $reply_date ); ?></td>
                    </tr>
                    <?php if ( $replied_by_user_id ) : ?>
                        <tr>
                            <th><?php esc_html_e( 'Sent by:', 'ihumbak-chat' ); ?></th>
                            <td><?php echo esc_html( get_the_author_meta( 'display_name', $replied_by_user_id ) ); ?></td>
                        </tr>
                    <?php endif; ?>
                </table>

                <div style="background-color: #fff; padding: 15px; border-left: 4px solid #007bff; margin: 15px 0; border-radius: 3px;">
                    <?php echo wp_kses_post( $admin_reply ); ?>
                </div>
            </div>
        <?php else : ?>
            <?php if ( ! $disable_admin_reply ) : ?>
            <div class="card" style="margin-top: 30px;">
                <h2><?php esc_html_e( 'Send Reply to Customer', 'ihumbak-chat' ); ?></h2>
                <form method="post" action="">
                    <?php wp_nonce_field( 'send_reply_' . $post_id, 'ihumbak_reply_nonce' ); ?>
                    
                    <div style="margin-bottom: 20px;">
                        <label for="ihumbak-reply"><?php esc_html_e( 'Your Reply:', 'ihumbak-chat' ); ?></label>
                        <?php
                        wp_editor(
                            '',
                            'ihumbak_reply',
                            array(
                                'media_buttons' => false,
                                'textarea_rows' => 8,
                                'teeny'         => false,
                                'quicktags'     => true,
                            )
                        );
                        ?>
                    </div>

                    <div>
                        <button type="submit" name="ihumbak_send_reply" class="button button-primary">
                            <?php esc_html_e( 'Send Reply to Customer', 'ihumbak-chat' ); ?>
                        </button>
                    </div>
                </form>
            </div>
            <?php else : ?>
            <div class="card" style="margin-top: 30px; background-color: #f0f0f1;">
                <h2><?php esc_html_e( 'Reply Disabled', 'ihumbak-chat' ); ?></h2>
                <p><?php esc_html_e( 'Direct replies from the admin panel have been disabled in the plugin settings.', 'ihumbak-chat' ); ?></p>
                <p>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=ihumbak-settings&tab=advanced' ) ); ?>" class="button">
                        <?php esc_html_e( 'Go to Settings', 'ihumbak-chat' ); ?>
                    </a>
                </p>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
