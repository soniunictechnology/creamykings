# Contact Form Setup Guide

This guide explains how to set up and use the contact form system for your IceDelights website.

## Files Created/Modified

### 1. `sendmail.php` - Main Email Handler
- **Purpose**: Processes form submissions and sends emails
- **Features**:
  - Form validation and sanitization
  - Rate limiting (5 requests per hour)
  - CSRF protection
  - Beautiful HTML email template
  - Security measures against spam

### 2. `assets/js/contact-form.js` - Form JavaScript
- **Purpose**: Handles form submission via AJAX
- **Features**:
  - Prevents page reload on submission
  - Shows loading states
  - Displays success/error messages
  - Auto-resets form on success

### 3. `assets/css/style.css` - Styling
- **Purpose**: Styles for form messages and validation
- **Features**:
  - Success/error message styling
  - Form validation visual feedback
  - Smooth animations

### 4. `contact.html` - Contact Page
- **Purpose**: Updated contact form
- **Features**:
  - Required field validation
  - Message container for responses
  - Improved form structure

## Setup Requirements

### Server Requirements
- PHP 7.0 or higher
- Mail function enabled (`mail()` function)
- Sessions enabled
- Proper email configuration

### Email Configuration
The form sends emails to: `info@royalicecandy.com`

To change the recipient email, edit line 89 in `sendmail.php`:
```php
$to = "your-email@domain.com"; // Change this to your email
```

## How It Works

1. **User fills out form** and clicks submit
2. **JavaScript prevents default submission** and sends data via AJAX
3. **PHP validates input** and checks rate limits
4. **Email is sent** with beautiful HTML template
5. **Response is returned** to show success/error message
6. **Form resets** on successful submission

## Security Features

- **Input Sanitization**: All user inputs are cleaned and validated
- **Rate Limiting**: Maximum 5 form submissions per hour per session
- **CSRF Protection**: Checks request origin
- **Length Validation**: Prevents extremely long inputs
- **Email Validation**: Ensures valid email format

## Customization

### Email Template
The email template is in `sendmail.php` starting around line 95. You can customize:
- Colors and styling
- Header content
- Information layout
- Footer content

### Form Validation
Validation rules are in `sendmail.php` around line 75. You can modify:
- Field requirements
- Character limits
- Error messages

### Styling
Message styles are in `assets/css/style.css`. You can customize:
- Message colors
- Animations
- Button states
- Form validation styles

## Testing

1. Fill out the contact form on your website
2. Submit the form
3. Check your email for the submission
4. Verify the form shows success message
5. Test validation by submitting empty forms

## Troubleshooting

### Email Not Sending
- Check if PHP mail function is enabled
- Verify server email configuration
- Check server error logs

### Form Not Working
- Ensure JavaScript is enabled
- Check browser console for errors
- Verify file paths are correct

### Styling Issues
- Clear browser cache
- Check CSS file paths
- Verify CSS syntax

## Production Deployment

Before going live:
1. Remove error reporting in `sendmail.php` (lines 2-3)
2. Test thoroughly on staging server
3. Verify email delivery
4. Monitor for spam/abuse

## Support

If you encounter issues:
1. Check server error logs
2. Verify PHP configuration
3. Test email functionality
4. Check browser console for JavaScript errors 