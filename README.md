# iHumbak Chat - Quick Admin Communication

A WordPress plugin that enables quick communication with site administrators via a floating chat widget. Features a simple email and message form with Google reCAPTCHA v3 protection.

## Features

- 🎨 **Floating Chat Widget** - Customizable position and color
- 📧 **Email Notifications** - Instant admin notifications for new messages
- 🛡️ **Spam Protection** - Google reCAPTCHA v3 + Rate limiting
- 📱 **Responsive Design** - Works on desktop, tablet, and mobile
- ⚙️ **Easy Configuration** - Simple admin settings page
- 💾 **Message Management** - View, read, and delete messages from admin panel

## Requirements

- WordPress 5.9+
- PHP 7.4+
- MySQL 5.7+

## Installation

1. Download the plugin ZIP file
2. Go to WordPress Admin → Plugins → Add New
3. Click "Upload Plugin" and select the ZIP file
4. Click "Install Now"
5. Activate the plugin

## Configuration

1. Go to **iHumbak Chat → Settings** in WordPress admin
2. Enter your admin email address
3. (Optional) Add Google reCAPTCHA keys from [Google reCAPTCHA Console](https://www.google.com/recaptcha/admin)
4. Configure widget position, color, and other settings
5. Save changes

The widget will now appear on your site's frontend!

## Getting reCAPTCHA Keys

1. Visit [Google reCAPTCHA](https://www.google.com/recaptcha/admin)
2. Register a new site
3. Choose **reCAPTCHA v3**
4. Add your domain
5. Copy the **Site Key** and **Secret Key**
6. Paste them in plugin settings

## Usage

### For Site Visitors

1. Click the chat bubble icon on any page
2. Enter your email address
3. Type your message (minimum 5 characters)
4. Click "Send"
5. You'll receive a confirmation message

### For Administrators

1. Receive email notifications for new messages
2. View all messages in **iHumbak Chat → Messages**
3. Click "View" to see full message details
4. Mark messages as read or delete them

## Settings Options

- **Admin Email** - Email address to receive notifications
- **reCAPTCHA Site Key** - Public key for reCAPTCHA
- **reCAPTCHA Secret Key** - Private key for reCAPTCHA
- **Rate Limit** - Maximum messages per IP per hour (default: 5)
- **Widget Position** - Bottom right, bottom left, top right, or top left
- **Widget Color** - Primary color for the widget
- **Widget Title** - Heading text (default: "Get in Touch")
- **Email Notifications** - Enable/disable email alerts

## REST API Endpoints

The plugin provides REST API endpoints:

### Send Message
```
POST /wp-json/ihumbak-chat/v1/send-message
```

**Request Body:**
```json
{
  "email": "user@example.com",
  "message": "Hello admin!",
  "recaptcha_token": "token_here"
}
```

### Get Settings
```
GET /wp-json/ihumbak-chat/v1/settings
```

## Security Features

- **nonce verification** for all requests
- **Email validation** and sanitization
- **Content sanitization** to prevent XSS
- **Rate limiting** per IP address
- **reCAPTCHA v3** spam protection
- **CSRF protection** on admin forms

## Troubleshooting

### Widget not appearing
- Check if plugin is activated
- Clear browser cache
- Check browser console for JavaScript errors

### Messages not being received
- Verify admin email in settings
- Check WordPress email configuration
- Look in spam folder

### reCAPTCHA not working
- Verify Site Key and Secret Key are correct
- Ensure site domain is registered in reCAPTCHA console
- Check that reCAPTCHA v3 is selected (not v2)

## Support

For support, please contact the plugin author or visit the plugin repository.

## License

GPL v2 or later

## Changelog

### 1.0.0
- Initial release
- Floating chat widget
- Admin message management
- Email notifications
- reCAPTCHA v3 integration
- Rate limiting
- Responsive design

## Credits

Developed with ❤️ for WordPress community
