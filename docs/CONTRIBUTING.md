# Contributing to iHumbak Chat

Thank you for your interest in contributing to iHumbak Chat!

## Development Setup

1. Clone the repository
2. Install dependencies (if any): `composer install`
3. Create a local WordPress installation for testing
4. Symlink the plugin to `wp-content/plugins/`

## Branch Naming Convention

- `main` - stable, production-ready code
- `develop` - development branch
- `feature/feature-name` - new features
- `bugfix/bug-description` - bug fixes
- `hotfix/issue-description` - urgent production fixes

## Commit Messages

Use clear, descriptive commit messages:

```
feat: Add new feature
fix: Fix bug description
docs: Update documentation
style: Code style changes
refactor: Code refactoring
test: Add or update tests
chore: Maintenance tasks
```

## Pull Request Process

1. Fork the repository
2. Create a feature branch from `develop`
3. Make your changes
4. Test thoroughly
5. Submit a pull request to `develop` branch
6. Describe your changes clearly

## Coding Standards

- Follow WordPress Coding Standards
- Use proper indentation (tabs for PHP, spaces for CSS/JS)
- Add comments for complex logic
- Sanitize all inputs
- Escape all outputs
- Use nonce verification for forms

## Testing

- Test on multiple browsers
- Test on mobile devices
- Test with and without reCAPTCHA
- Verify email notifications work
- Check security measures

## Security

- Never commit sensitive data
- Use WordPress security functions
- Validate and sanitize all user input
- Use prepared statements for database queries
- Follow WordPress security best practices

## Questions?

Open an issue for discussion before making major changes.
