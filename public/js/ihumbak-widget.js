(function() {
    'use strict';

    let isOpen = false;
    let settings = {};

    // Initialize widget
    document.addEventListener('DOMContentLoaded', function() {
        // Get elements
        const bubble = document.getElementById('ihumbak-chat-bubble');
        const formOverlay = document.getElementById('ihumbak-form-overlay');
        const chatForm = document.getElementById('ihumbak-chat-form');
        const closeBtn = document.getElementById('ihumbak-close-btn');
        const form = document.getElementById('ihumbak-message-form');
        const emailInput = document.getElementById('ihumbak-email');
        const messageInput = document.getElementById('ihumbak-message');
        const consentInput = document.getElementById('ihumbak-consent');
        const submitBtn = document.getElementById('ihumbak-submit-btn');

        if (!bubble || !formOverlay || !chatForm) return;

        // Get settings from localized script
        settings = window.ihumbakChat || {};

        // Apply widget color
        if (settings.widgetColor) {
            bubble.style.backgroundColor = settings.widgetColor;
            document.querySelector('.ihc-header').style.backgroundColor = settings.widgetColor;
            document.querySelector('.ihc-submit').style.backgroundColor = settings.widgetColor;
        }

        // Update consent text if provided
        if (settings.consentText) {
            const consentTextElement = document.getElementById('consent-text');
            if (consentTextElement) {
                consentTextElement.textContent = settings.consentText;
            }
        }

        // Update intro text if provided and not empty
        if (settings.introText && settings.introText.trim()) {
            const introTextElement = document.getElementById('ihumbak-intro-text');
            if (introTextElement) {
                introTextElement.innerHTML = settings.introText;
                introTextElement.style.display = 'block';
            }
        } else {
            const introTextElement = document.getElementById('ihumbak-intro-text');
            if (introTextElement) {
                introTextElement.style.display = 'none';
            }
        }

        // Update message label if provided
        if (settings.messageLabel) {
            const messageLabelElement = document.getElementById('message-label');
            if (messageLabelElement) {
                messageLabelElement.textContent = settings.messageLabel;
            }
        }

        // Update email label if provided
        if (settings.emailLabel) {
            const emailLabelElement = document.querySelector('label[for="ihumbak-email"]');
            if (emailLabelElement) {
                emailLabelElement.textContent = settings.emailLabel;
            }
        }

        // Update email placeholder if provided
        if (settings.emailPlaceholder && emailInput) {
            emailInput.setAttribute('placeholder', settings.emailPlaceholder);
        }

        // Update message placeholder if provided
        if (settings.messagePlaceholder && messageInput) {
            messageInput.setAttribute('placeholder', settings.messagePlaceholder);
        }

        // Update submit button text if provided
        if (settings.submitButtonText) {
            const submitTextElement = document.getElementById('submit-text');
            if (submitTextElement) {
                submitTextElement.textContent = settings.submitButtonText;
            }
        }

        // Apply header font color
        if (settings.headerFontColor) {
            const titleElement = document.querySelector('.ihc-title');
            if (titleElement) {
                titleElement.style.color = settings.headerFontColor;
            }
        }

        // Toggle widget
        bubble.addEventListener('click', function() {
            toggleWidget();
        });

        closeBtn.addEventListener('click', function() {
            toggleWidget();
        });

        // Close on overlay click (outside form)
        formOverlay.addEventListener('click', function(e) {
            if (e.target === formOverlay) {
                toggleWidget();
            }
        });

        // Character counter
        messageInput.addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('char-count').textContent = count + ' / 1000';
        });

        // Email validation
        emailInput.addEventListener('blur', function() {
            validateEmail();
        });

        // Form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateForm()) {
                sendMessage();
            }
        });
    });

    function toggleWidget() {
        const formOverlay = document.getElementById('ihumbak-form-overlay');
        const bubble = document.getElementById('ihumbak-chat-bubble');
        
        isOpen = !isOpen;
        
        if (isOpen) {
            formOverlay.classList.add('active');
            bubble.style.display = 'none';
        } else {
            formOverlay.classList.remove('active');
            bubble.style.display = 'flex';
        }
    }

    function validateEmail() {
        const emailInput = document.getElementById('ihumbak-email');
        const emailError = document.getElementById('email-error');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(emailInput.value)) {
            emailError.textContent = settings.emailErrorMessage || 'Please enter a valid email address.';
            emailInput.classList.add('error');
            return false;
        } else {
            emailError.textContent = '';
            emailInput.classList.remove('error');
            return true;
        }
    }

    function validateForm() {
        const emailInput = document.getElementById('ihumbak-email');
        const messageInput = document.getElementById('ihumbak-message');
        const consentInput = document.getElementById('ihumbak-consent');
        const messageError = document.getElementById('message-error');
        const consentError = document.getElementById('consent-error');

        let isValid = true;

        // Validate email
        if (!validateEmail()) {
            isValid = false;
        }

        // Validate message
        if (messageInput.value.trim().length < 5) {
            messageError.textContent = settings.messageErrorMessage || 'Message must be at least 5 characters.';
            messageInput.classList.add('error');
            isValid = false;
        } else {
            messageError.textContent = '';
            messageInput.classList.remove('error');
        }

        // Validate consent
        if (!consentInput.checked) {
            consentError.textContent = settings.consentErrorMessage || 'You must consent to data processing.';
            consentInput.classList.add('error');
            isValid = false;
        } else {
            consentError.textContent = '';
            consentInput.classList.remove('error');
        }

        return isValid;
    }

    async function sendMessage() {
        const emailInput = document.getElementById('ihumbak-email');
        const messageInput = document.getElementById('ihumbak-message');
        const consentInput = document.getElementById('ihumbak-consent');
        const submitBtn = document.getElementById('ihumbak-submit-btn');
        
        // Disable form
        submitBtn.disabled = true;
        submitBtn.querySelector('.submit-text').style.display = 'none';
        submitBtn.querySelector('.submit-loading').style.display = 'inline-flex';

        try {
            // Get reCAPTCHA token if enabled
            let recaptchaToken = '';
            if (settings.recaptchaSiteKey && window.grecaptcha) {
                recaptchaToken = await grecaptcha.execute(settings.recaptchaSiteKey, {action: 'submit'});
            }

            // Send AJAX request
            const response = await fetch(settings.ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': settings.nonce
                },
                body: JSON.stringify({
                    email: emailInput.value,
                    message: messageInput.value,
                    consent: consentInput.checked ? '1' : '0',
                    recaptcha_token: recaptchaToken
                })
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message || 'Message sent successfully! 🎉', 'success');
                emailInput.value = '';
                messageInput.value = '';
                consentInput.checked = false;
                document.getElementById('char-count').textContent = '0 / 1000';
                
                // Close widget after 2 seconds
                setTimeout(function() {
                    toggleWidget();
                }, 2000);
            } else {
                showToast(data.message || 'Error sending message. Please try again.', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Network error. Please try again later.', 'error');
        } finally {
            // Re-enable form
            submitBtn.disabled = false;
            submitBtn.querySelector('.submit-text').style.display = 'inline';
            submitBtn.querySelector('.submit-loading').style.display = 'none';
        }
    }

    function showToast(message, type) {
        const toast = document.getElementById('ihumbak-toast');
        toast.textContent = message;
        toast.className = 'ihc-toast ihc-toast-' + type;
        toast.style.display = 'block';

        setTimeout(function() {
            toast.style.display = 'none';
        }, 5000);
    }

})();
