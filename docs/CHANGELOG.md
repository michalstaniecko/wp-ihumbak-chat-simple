# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.6.0] - 2025-11-17

### Added
- **Hide admin panel link option** - New setting in Email Template tab to hide "View in admin panel" link from notification emails
- **Disable admin reply option** - New setting in Advanced tab to completely disable direct replies from admin panel
  - When enabled, reply form is hidden and replaced with informative message
  - Backend validation prevents bypassing via direct POST
  - Administrators can still mark messages as responded manually

### Fixed
- **Circular dependency resolved** - Fixed Error 500 on settings page caused by circular initialization between `Ihumbak_Settings` and `Ihumbak_Settings_Validator`
  - Removed unnecessary dependency in validator constructor
  - Validator now uses WordPress `update_option()` directly instead of wrapping through Settings class
- **Undefined method error** - Fixed `Call to undefined method Ihumbak_Settings::save_settings()` - corrected to `save()`

### Technical
- Improved class initialization order and dependency management
- Better separation of concerns between Settings and Validator classes

## [1.5.1] - 2025-11-16

### Changed
- **Settings views refactor** - Split admin settings into separate view files:
  - `admin/views/settings-general.php` - General tab (email, reCAPTCHA, notifications)
  - `admin/views/settings-appearance.php` - Appearance tab (colors, labels, placeholders)
  - `admin/views/settings-email-template.php` - Email templates (headers, footers, subjects)
  - `admin/views/settings-advanced.php` - Advanced settings
- Main settings page reduced from 572 to 62 lines
- Each view file is now independently maintainable

### Technical
- Better separation of UI concerns
- Easier to find and modify specific settings
- Total view files now 631 lines vs original 572 in single file

## [1.5.0] - 2025-11-16

### Changed
- **Code refactoring** - Separated settings management into focused classes:
  - `Ihumbak_Settings_Defaults` - Manages all default setting values
  - `Ihumbak_Settings_Validator` - Handles validation and saving of settings
  - `Ihumbak_Settings` - Main manager with cleaner interface
- Better code organization and maintainability
- Reduced main settings file from 290 lines to ~60 lines

### Technical
- Settings are now split across 3 dedicated files in `/includes/`
- Improved separation of concerns (defaults, validation, management)
- No breaking changes for end users

## [1.4.3] - 2025-11-16

### Added
- **Email subject customization** - Separate fields for:
  - Admin notification email subject (supports {site} and {email} placeholders)
  - Customer reply email subject (supports {site} placeholder)
- Dynamic subject line generation based on custom templates

### Changed
- Email subjects now use customizable templates instead of hardcoded text
- Better control over email branding

## [1.4.2] - 2025-11-16

### Changed
- **Separate email templates** - Split Email Template settings into two sections:
  - **Admin Notification Email**: Header and footer for emails sent to admin when messages arrive
  - **Customer Reply Email**: Header and footer for emails sent to customers with admin replies
- Better organization of email customization options

### Fixed
- Reply emails now use their own header/footer settings, separate from admin notifications

## [1.4.1] - 2025-11-16

### Added
- **Email Template tab** - New settings tab for customizing email notifications
- **Email header** - Optional HTML/text field that appears at the top of admin notification emails
- **Email footer** - Optional HTML/text field that appears at the bottom of admin notification emails
- Both header and footer hide automatically when empty

### Changed
- Email template now includes configurable header and footer sections
- Better email customization options for branding

## [1.4.0] - 2025-11-15

### Added
- **Email field customization** - Editable email label and placeholder from admin panel
- **Validation error messages** - Customizable error messages for email, message, and consent fields
- All error messages now load from settings and can be edited per field

### Changed
- Error messages now use settings values instead of hardcoded text
- More granular customization options in Appearance tab

## [1.3.4] - 2025-11-15

### Added
- **Introduction text field** - New editable paragraph that appears below widget title and above email field
- WYSIWYG editor for intro text in admin panel with basic formatting options
- Intro text is optional - hides automatically when empty

### Changed
- **Consent label font size** - Reduced from 13px to 12px for better proportion
- Added `.ihc-intro-text` CSS class for intro paragraph styling

## [1.3.3] - 2025-11-15

### Fixed
- **Widget Visibility checkbox not saving** - Fixed issue where unchecking "Show widget only for administrators" wasn't saving properly due to unchecked checkboxes not being sent in POST data

## [1.3.2] - 2025-11-15

### Fixed
- **Close button width** - Added `width: auto !important` to prevent theme styles from overriding button dimensions

## [1.3.1] - 2025-11-15

### Added
- **Reply storage** - Admin replies are now saved as post meta on the message (ihumbak_admin_reply, ihumbak_reply_date, ihumbak_replied_by)
- **Reply section in admin** - Shows sent reply with date and admin name who sent it
- **Conditional reply form** - Reply form is hidden after reply is sent, prevents accidental duplicate replies

### Changed
- Message view page now displays sent reply with metadata
- Better UX: after sending reply, form is replaced with "Reply Sent to Customer" section (green highlighted)
- Reply information shows who sent it and when

## [1.3.0] - 2025-11-15

### Added
- **Original message in reply email** - Customer's original message is now included in reply emails for context
- New `.original-message` section with styled message label and customer's message content

### Changed
- Reply email template now displays original customer message at bottom
- Better email readability with proper spacing between reply and original message

## [1.2.9] - 2025-11-15

### Added
- **Admin reply functionality** - Added reply form with WordPress WYSIWYG editor on message view page
- **Send reply to customer** - Ability to send formatted HTML replies directly to customer email
- **Reply email template** - New email template for customer replies with styling

### Changed
- **Email notification template** - Simplified admin email template with smaller header, removed large blue section
- Email notifications now show only essential info: customer email, date, message content
- Button in email notifications changed to simple text link instead of large styled button

### Improved
- Better email readability and cleaner layout
- Reply emails automatically mark message as responded

## [1.2.8] - 2025-11-15

### Fixed
- **Close button background color** - Added `!important` flags and `background-color: transparent` to prevent template/theme styles from overriding close button appearance

## [1.2.7] - 2025-11-15

### Fixed
- **Toast class names in JavaScript** - Updated toast element class names from `ihumbak-toast` to `ihc-toast` in showToast() function
- **All JavaScript selectors fully aligned** - Ensured all getElementById and querySelector calls match HTML element IDs and classes
- Widget now works correctly after CSS class refactoring to `ihc-` prefix

## [1.2.6] - 2025-11-15

### Fixed
- **JavaScript selectors after class rename** - Updated `querySelector` calls to use new `ihc-` prefixed classes (`.ihc-header`, `.ihc-submit`, `.ihc-title`)

## [1.2.5] - 2025-11-15

### Changed
- **CSS class name refactor** - All plugin classes now use `ihc-` prefix (e.g., `.ihc-form`, `.ihc-close`, `.ihc-toast`)
- **Close button styling** - Removed redundant `background` property from `.ihc-close` button

## [1.2.4] - 2025-11-15

### Changed
- **Error message line-height** - Reduced `.ihumbak-error` line-height to 1.25 for better text spacing

## [1.2.3] - 2025-11-15

### Fixed
- **Widget overlay positioning** - Implemented fullscreen fixed overlay with flexbox for better positioning control
- **Viewport height issues** - Widget now uses overlay container that prevents overflow on any screen size
- **Form positioning** - Formularz zachowuje pozycję względem bubble (bottom-right, top-left, etc.)

### Added
- Click outside form to close - Clicking on overlay (outside form) now closes the widget
- Fullscreen overlay container with `position: fixed` covering entire viewport

### Changed
- Widget structure now uses overlay wrapper with flexbox layout
- Form positioning uses `align-items` and `justify-content` for precise placement
- Improved scrolling behavior - only form body scrolls when content exceeds height
- Better responsive behavior on mobile devices

## [1.2.2] - 2025-11-15

### Fixed
- **Widget height on small screens** - Widget now respects viewport height with `max-height: calc(100vh - 40px)`
- **Content scrolling** - Widget body now scrolls when content exceeds available height
- **Responsive behavior** - Improved max-height handling on mobile devices (≤640px and ≤480px)

### Changed
- Widget form now uses flexbox layout for better space management
- Header uses `flex-shrink: 0` to maintain fixed height
- Body section uses `overflow-y: auto` and `flex: 1` for scrollable content area

## [1.2.1] - 2025-11-15

### Fixed
- **Header font color not applying** - Color now correctly applies to `.ihumbak-title` instead of `.ihumbak-header`
- **CSS conflict** - Moved `color: white;` from `.ihumbak-header` to `.ihumbak-title` to prevent override
- **Removed headline tags** - Changed `<h3>` to `<div>` for widget title (no H1-H6 tags in frontend)

### Changed
- Widget title now uses `<div class="ihumbak-title">` instead of `<h3>`
- JavaScript applies header font color to correct element

## [1.2.0] - 2025-11-15

### Added
- **Settings tabs** - Organized settings into General, Appearance, and Advanced tabs
- **Header font color** - Customizable text color in widget header
- **Message label customization** - Edit label for message field
- **Message placeholder customization** - Edit placeholder text for message field
- **Submit button text customization** - Customize the send button text
- Better form styling with `margin-bottom: 0 !important` for inputs

### Changed
- Settings page reorganized with tabbed interface for better UX
- Form now uses `novalidate` for better JavaScript validation
- Enhanced REST API to include new customization options

### Improved
- Form validation now fully JavaScript-controlled
- Better visual organization of settings
- More customization options for widget appearance

## [1.1.0] - 2025-11-15

### Added
- **Consent checkbox** - Required GDPR-compliant consent checkbox in contact form
- **Editable consent text** - Customize consent message from settings page
- **Consent tracking** - Records and displays consent status in admin panel
- **Response tracking** - Mark messages as "responded" in admin panel
- **Admin-only mode** - Option to show widget only to administrators (for testing)
- New setting: Consent text customization
- New setting: Admin-only widget visibility
- "Mark as Responded" button in message details
- Visual indicators for consent and response status

### Changed
- Enhanced message metadata to include consent information
- Improved admin message view with consent and response status
- Updated REST API to validate consent before accepting messages
- Form validation now includes consent checkbox verification

### Security
- Added consent validation to prevent form submission without agreement
- Enhanced data protection compliance (GDPR)

## [1.0.0] - 2025-11-15

### Added
- Initial release of iHumbak Chat plugin
- Floating chat widget with customizable position and color
- Email and message form with validation
- Google reCAPTCHA v3 integration for spam protection
- Rate limiting (5 messages per IP per hour by default)
- Admin panel for viewing and managing messages
- Email notifications for new messages
- Settings page with full configuration options
- REST API endpoints for message submission
- Responsive design for mobile, tablet, and desktop
- Security features: nonce verification, sanitization, CSRF protection
- Custom post type for storing messages
- Message metadata: IP, user agent, referer, read status

### Security
- Email validation and sanitization
- Message content sanitization
- Rate limiting per IP address
- reCAPTCHA v3 spam protection
- WordPress nonce verification
- Proper escaping of output

## [Unreleased]

### Planned Features
- Slack integration
- Multiple language support (full i18n)
- File attachment support
- Conversation threading
- Admin replies from panel
- Analytics and statistics dashboard
