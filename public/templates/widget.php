<?php
/**
 * Widget Template
 *
 * @package Ihumbak_Chat
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$settings = Ihumbak_Settings::get_instance();
$position = $settings->get_option( 'ihumbak_widget_position', 'bottom-right' );
?>

<!-- iHumbak Chat Widget -->
<div id="ihumbak-chat-widget" class="ihc-widget-container" data-position="<?php echo esc_attr( $position ); ?>">
    <!-- Chat Bubble Button -->
    <button id="ihumbak-chat-bubble" class="ihc-bubble" aria-label="<?php esc_attr_e( 'Open chat', 'ihumbak-chat' ); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
    </button>

    <!-- Chat Form Overlay (Hidden by default) -->
    <div id="ihumbak-form-overlay" class="ihc-form-overlay">
        <div id="ihumbak-chat-form" class="ihc-form">
            <!-- Header -->
            <div class="ihc-header">
                <div class="ihc-title"><?php echo esc_html( $settings->get_option( 'ihumbak_widget_title', 'Get in Touch' ) ); ?></div>
                <button id="ihumbak-close-btn" class="ihc-close" aria-label="<?php esc_attr_e( 'Close chat', 'ihumbak-chat' ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

        <!-- Body -->
        <div class="ihc-body">
            <div id="ihumbak-intro-text" class="ihc-intro-text"></div>
            
            <form id="ihumbak-message-form" novalidate>
                <div class="ihc-field">
                    <label for="ihumbak-email"><?php esc_html_e( 'Your Email', 'ihumbak-chat' ); ?></label>
                    <input type="email" 
                           id="ihumbak-email" 
                           name="email" 
                           placeholder="your@email.com" 
                           required>
                    <span class="ihc-error" id="email-error"></span>
                </div>

                <div class="ihc-field">
                    <label for="ihumbak-message" id="message-label"><?php echo esc_html( $settings->get_option( 'ihumbak_message_label', 'Message' ) ); ?></label>
                    <textarea id="ihumbak-message" 
                              name="message" 
                              rows="4" 
                              placeholder="<?php echo esc_attr( $settings->get_option( 'ihumbak_message_placeholder', 'Your message...' ) ); ?>" 
                              required
                              minlength="5"></textarea>
                    <span class="ihc-error" id="message-error"></span>
                    <span class="ihc-char-count" id="char-count">0 / 1000</span>
                </div>

                <div class="ihc-field">
                    <label class="ihc-consent-label">
                        <input type="checkbox" 
                               id="ihumbak-consent" 
                               name="consent" 
                               required>
                        <span id="consent-text"><?php echo esc_html( $settings->get_option( 'ihumbak_consent_text', 'Wyrażam zgodę na przetwarzanie moich danych osobowych w celu udzielenia odpowiedzi na przesłaną wiadomość.' ) ); ?></span>
                    </label>
                    <span class="ihc-error" id="consent-error"></span>
                </div>

                <!-- Footer -->
                <div class="ihc-footer">
                    <button type="submit" id="ihumbak-submit-btn" class="ihc-submit">
                        <span class="submit-text" id="submit-text"><?php echo esc_html( $settings->get_option( 'ihumbak_submit_button_text', 'Send' ) ); ?></span>
                        <span class="submit-loading" style="display: none;">
                            <svg class="ihc-spinner" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="2" x2="12" y2="6"></line>
                                <line x1="12" y1="18" x2="12" y2="22"></line>
                                <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                                <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                                <line x1="2" y1="12" x2="6" y2="12"></line>
                                <line x1="18" y1="12" x2="22" y2="12"></line>
                                <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                                <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                            </svg>
                            <?php esc_html_e( 'Sending...', 'ihumbak-chat' ); ?>
                        </span>
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div id="ihumbak-toast" class="ihc-toast" style="display: none;"></div>
</div>
