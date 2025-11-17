# iHumbak Chat Plugin - Installation & Setup Guide

## ✅ Plugin Successfully Created!

Your WordPress plugin has been created with all the features specified in the documentation.

## 📁 File Structure

```
wp-ihumbak-chat-simple/
├── ihumbak-chat.php              # Main plugin file
├── README.md                      # Documentation
├── LICENSE                        # GPL v2 License
├── CHANGELOG.md                   # Version history
├── CONTRIBUTING.md                # Contribution guidelines
├── composer.json                  # PHP dependencies
├── package.json                   # NPM configuration
├── .gitignore                     # Git ignore rules
│
├── includes/                      # Backend classes
│   ├── class-ihumbak-chat.php    # Main plugin class
│   ├── class-ihumbak-admin.php   # Admin panel handler
│   ├── class-ihumbak-security.php # Security & validation
│   ├── class-ihumbak-messages.php # Custom post type
│   ├── class-ihumbak-settings.php # Settings management
│   ├── class-ihumbak-rest-api.php # REST API endpoints
│   └── class-ihumbak-email.php   # Email notifications
│
├── admin/                         # Admin panel
│   ├── pages/
│   │   ├── messages-list.php     # Messages list page
│   │   ├── message-view.php      # Single message view
│   │   └── settings.php          # Settings page
│   └── css/
│       └── admin-style.css       # Admin styles
│
├── public/                        # Frontend files
│   ├── templates/
│   │   └── widget.php            # Widget HTML template
│   ├── js/
│   │   └── ihumbak-widget.js     # Widget JavaScript
│   └── css/
│       └── ihumbak-widget.css    # Widget styles
│
├── assets/                        # Assets
│   └── icons/
│       └── chat-icon.svg         # Chat icon
│
├── languages/                     # Translations
│   └── ihumbak-chat.pot          # Translation template
│
├── .github/                       # GitHub Actions
│   └── workflows/
│       └── lint.yml              # PHP linting workflow
│
└── docs/                          # Documentation
    ├── SPECIFICATION.md          # Original specification
    └── WORKPLAN.md               # Development plan
```

## 🚀 Installation Steps

### Method 1: Manual Installation (Development)

1. **Copy the plugin to WordPress:**
   ```bash
   cp -r wp-ihumbak-chat-simple /path/to/wordpress/wp-content/plugins/ihumbak-chat
   ```

2. **Activate in WordPress:**
   - Go to WordPress Admin → Plugins
   - Find "iHumbak Chat - Quick Admin Communication"
   - Click "Activate"

### Method 2: ZIP Installation (Production)

1. **Create ZIP file:**
   ```bash
   cd wp-ihumbak-chat-simple
   zip -r ihumbak-chat.zip . -x "*.git*" "docs/*" "node_modules/*" "vendor/*"
   ```

2. **Upload to WordPress:**
   - Go to WordPress Admin → Plugins → Add New
   - Click "Upload Plugin"
   - Choose the ZIP file
   - Click "Install Now"
   - Activate the plugin

## ⚙️ Configuration

### Step 1: Basic Settings

1. Go to **iHumbak Chat → Settings**
2. Enter your **Admin Email** (where notifications will be sent)
3. Click **Save Settings**

The widget is now active with default settings!

### Step 2: Google reCAPTCHA (Recommended)

1. Visit [Google reCAPTCHA Console](https://www.google.com/recaptcha/admin)
2. Click **"+"** to register a new site
3. Fill in the form:
   - **Label:** Your site name
   - **reCAPTCHA type:** Select **reCAPTCHA v3**
   - **Domains:** Add your domain (e.g., example.com)
4. Accept terms and submit
5. Copy the **Site Key** and **Secret Key**
6. Go back to **iHumbak Chat → Settings**
7. Paste the keys in the reCAPTCHA fields
8. Save settings

### Step 3: Customize Widget (Optional)

In **iHumbak Chat → Settings**, customize:

- **Widget Position:** Choose corner position
- **Widget Color:** Select your brand color
- **Widget Title:** Change the greeting text
- **Rate Limit:** Adjust spam protection (default: 5 messages/hour)
- **Email Notifications:** Toggle on/off

## 🎨 Features Overview

### Frontend Widget
- ✅ Floating chat bubble in customizable position
- ✅ Smooth open/close animations
- ✅ Email and message form
- ✅ Real-time validation
- ✅ Character counter
- ✅ reCAPTCHA v3 integration
- ✅ Success/error notifications
- ✅ Fully responsive design

### Admin Panel
- ✅ **Messages page:** View all received messages
- ✅ **Message details:** IP, user agent, timestamp, referer
- ✅ **Filters:** Search by email, filter by status
- ✅ **Actions:** Mark as read, delete messages
- ✅ **Settings page:** Full configuration options
- ✅ Email notifications for new messages

### Security Features
- ✅ Google reCAPTCHA v3 spam protection
- ✅ Rate limiting per IP address
- ✅ Email validation and sanitization
- ✅ Message content sanitization
- ✅ WordPress nonce verification
- ✅ CSRF protection
- ✅ XSS prevention

## 📧 Testing the Plugin

1. **Frontend Test:**
   - Visit your website (logged out)
   - Click the chat bubble (bottom-right corner by default)
   - Enter an email and message
   - Click "Send"
   - You should see a success message

2. **Admin Test:**
   - Check your admin email for notification
   - Go to **iHumbak Chat → Messages**
   - You should see the new message
   - Click "View" to see full details

## 🐛 Troubleshooting

### Widget Not Showing
- Clear browser cache
- Check browser console for errors
- Verify plugin is activated
- Check if another plugin conflicts

### No Email Notifications
- Verify admin email in settings
- Test WordPress email with a test plugin
- Check spam folder
- Verify "Email Notifications" is enabled

### reCAPTCHA Not Working
- Verify you're using **reCAPTCHA v3** (not v2)
- Check Site Key and Secret Key are correct
- Ensure your domain is registered in reCAPTCHA console
- Check browser console for errors

### Rate Limit Issues
- Default is 5 messages per hour per IP
- Adjust in **Settings → Rate Limit**
- Clear transients: WP Admin → Tools → Transients

## 🔧 Development

### PHP Files
All PHP files follow WordPress coding standards with:
- Proper sanitization and escaping
- Security checks (nonce, capabilities)
- Object-oriented design
- Clear documentation

### JavaScript
- Vanilla JavaScript (no dependencies)
- Async/await for AJAX requests
- Real-time validation
- Error handling

### CSS
- Pure CSS (no framework dependencies)
- Responsive design
- Smooth animations
- Customizable colors

## 📚 API Endpoints

### Send Message
```
POST /wp-json/ihumbak-chat/v1/send-message
Content-Type: application/json
X-WP-Nonce: {nonce}

{
  "email": "user@example.com",
  "message": "Message text",
  "recaptcha_token": "token_here"
}
```

### Get Settings
```
GET /wp-json/ihumbak-chat/v1/settings

Response:
{
  "recaptchaSiteKey": "...",
  "widgetColor": "#007bff",
  "widgetPosition": "bottom-right",
  "widgetTitle": "Get in Touch"
}
```

## 🎯 Next Steps

1. **Customize Colors:** Match your brand
2. **Set up reCAPTCHA:** Improve spam protection
3. **Test Thoroughly:** Try different devices and browsers
4. **Monitor Messages:** Check admin panel regularly
5. **Adjust Rate Limits:** Based on your needs

## 📞 Support

- Check documentation in `/docs` folder
- Review code comments for detailed explanations
- Open GitHub issues for bugs or feature requests

## 📄 License

GPL v2 or later - See LICENSE file

---

**Plugin Version:** 1.0.0  
**WordPress Required:** 5.9+  
**PHP Required:** 7.4+  
**Last Updated:** November 15, 2025

Enjoy your new chat plugin! 🎉
