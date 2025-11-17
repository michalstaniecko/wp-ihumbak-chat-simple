<?php
/**
 * Messages List Admin Page
 *
 * @package Ihumbak_Chat
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get filter parameters
$status = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : '';
$search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';

// Build query args
$args = array(
    'post_type'      => 'ihumbak_message',
    'posts_per_page' => 20,
    'paged'          => isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1,
);

if ( 'read' === $status ) {
    $args['post_status'] = 'publish';
} elseif ( 'unread' === $status ) {
    $args['post_status'] = 'pending';
}

if ( ! empty( $search ) ) {
    $args['meta_query'] = array(
        array(
            'key'     => 'ihumbak_email',
            'value'   => $search,
            'compare' => 'LIKE',
        ),
    );
}

$messages = Ihumbak_Messages::get_messages( $args );
?>

<div class="wrap">
    <h1><?php esc_html_e( 'Messages', 'ihumbak-chat' ); ?></h1>

    <?php if ( isset( $_GET['deleted'] ) ) : ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e( 'Message deleted successfully.', 'ihumbak-chat' ); ?></p>
        </div>
    <?php endif; ?>

    <?php if ( isset( $_GET['marked'] ) ) : ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e( 'Message marked as read.', 'ihumbak-chat' ); ?></p>
        </div>
    <?php endif; ?>

    <div class="tablenav top">
        <div class="alignleft actions">
            <select name="status" id="filter-status" onchange="window.location.href='?page=ihumbak-messages&status=' + this.value">
                <option value=""><?php esc_html_e( 'All statuses', 'ihumbak-chat' ); ?></option>
                <option value="unread" <?php selected( $status, 'unread' ); ?>><?php esc_html_e( 'Unread', 'ihumbak-chat' ); ?></option>
                <option value="read" <?php selected( $status, 'read' ); ?>><?php esc_html_e( 'Read', 'ihumbak-chat' ); ?></option>
            </select>
        </div>
        <div class="alignright">
            <form method="get" style="display: inline;">
                <input type="hidden" name="page" value="ihumbak-messages">
                <input type="search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search by email...', 'ihumbak-chat' ); ?>">
                <button type="submit" class="button"><?php esc_html_e( 'Search', 'ihumbak-chat' ); ?></button>
            </form>
        </div>
    </div>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th style="width: 25%;"><?php esc_html_e( 'From', 'ihumbak-chat' ); ?></th>
                <th style="width: 40%;"><?php esc_html_e( 'Message', 'ihumbak-chat' ); ?></th>
                <th style="width: 15%;"><?php esc_html_e( 'Date', 'ihumbak-chat' ); ?></th>
                <th style="width: 10%;"><?php esc_html_e( 'Status', 'ihumbak-chat' ); ?></th>
                <th style="width: 10%;"><?php esc_html_e( 'Actions', 'ihumbak-chat' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if ( $messages->have_posts() ) : ?>
                <?php while ( $messages->have_posts() ) : $messages->the_post(); ?>
                    <?php
                    $post_id = get_the_ID();
                    $email = get_post_meta( $post_id, 'ihumbak_email', true );
                    $is_read = get_post_meta( $post_id, 'ihumbak_read', true );
                    $excerpt = wp_trim_words( get_the_content(), 15, '...' );
                    ?>
                    <tr>
                        <td><?php echo esc_html( $email ); ?></td>
                        <td><?php echo esc_html( $excerpt ); ?></td>
                        <td><?php echo esc_html( get_the_date() . ' ' . get_the_time() ); ?></td>
                        <td>
                            <?php if ( $is_read ) : ?>
                                <span class="dashicons dashicons-yes" style="color: green;"></span>
                            <?php else : ?>
                                <span class="dashicons dashicons-marker" style="color: orange;"></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=ihumbak-messages&action=view&id=' . $post_id ) ); ?>" class="button button-small">
                                <?php esc_html_e( 'View', 'ihumbak-chat' ); ?>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5" style="text-align: center;">
                        <?php esc_html_e( 'No messages found.', 'ihumbak-chat' ); ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php
    // Pagination
    if ( $messages->max_num_pages > 1 ) :
        ?>
        <div class="tablenav bottom">
            <div class="tablenav-pages">
                <?php
                echo paginate_links( array(
                    'base'      => add_query_arg( 'paged', '%#%' ),
                    'format'    => '',
                    'prev_text' => __( '&laquo;', 'ihumbak-chat' ),
                    'next_text' => __( '&raquo;', 'ihumbak-chat' ),
                    'total'     => $messages->max_num_pages,
                    'current'   => max( 1, isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1 ),
                ) );
                ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php wp_reset_postdata(); ?>
