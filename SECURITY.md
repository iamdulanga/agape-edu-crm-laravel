# Security Documentation - AGAPE EDU CRM

## Overview
This document outlines the security measures implemented in the AGAPE EDU CRM application and provides guidelines for maintaining security in development and production environments.

## Table of Contents
1. [Authentication & Authorization](#authentication--authorization)
2. [Input Validation & Sanitization](#input-validation--sanitization)
3. [Database Security](#database-security)
4. [XSS & CSRF Protection](#xss--csrf-protection)
5. [File Upload Security](#file-upload-security)
6. [Session Management](#session-management)
7. [Security Headers](#security-headers)
8. [Production Deployment Checklist](#production-deployment-checklist)
9. [Security Best Practices](#security-best-practices)

---

## Authentication & Authorization

### Role-Based Access Control (RBAC)
The application implements a three-tier role system:
- **Owner**: Full system access
- **Manager**: Can manage leads and counselors
- **Counselor**: Can view and update leads

### Implementation
- Role middleware (`CheckRole`) protects routes
- Policies (`LeadPolicy`, `UserPolicy`) enforce authorization at the model level
- `$this->authorize()` calls in controllers verify permissions

### Key Features
- Role hierarchy enforcement prevents privilege escalation
- Managers cannot create owners or other managers
- Only one owner account allowed per system
- Users cannot delete their own accounts (owner/manager roles)

---

## Input Validation & Sanitization

### FormRequest Classes
All user input is validated through dedicated FormRequest classes:
- `StoreLeadRequest`: Validates lead creation
- `UpdateLeadRequest`: Validates lead updates
- `StoreUserRequest`: Validates user creation

### Validation Features
- **Regex patterns** for names, cities (letters only)
- **Email validation** with RFC and DNS checks
- **Phone number** format validation
- **Age limits** (1-120 years)
- **Date validation** (inquiry date cannot be in future)
- **Enum validation** for status, priority, study level
- **File upload** MIME type and size validation

### Sanitization
All string inputs are sanitized before validation:
- `strip_tags()` removes HTML/PHP tags
- `trim()` removes whitespace
- `filter_var()` with `FILTER_SANITIZE_EMAIL` for email inputs

---

## Database Security

### Mass Assignment Protection
All models use `$fillable` arrays to explicitly define which attributes can be mass-assigned:

```php
// User Model
protected $fillable = ['name', 'username', 'email', 'password', 'avatar', 'last_login_at'];

// Lead Model
protected $fillable = ['first_name', 'last_name', 'email', 'phone', ...];
```

### Query Security
- **No raw SQL**: Application uses Eloquent ORM exclusively
- **Parameter binding**: All queries use parameterized statements
- **No DB::raw()**: Avoids raw SQL injection vulnerabilities

### Password Security
- Passwords hashed using bcrypt (12 rounds)
- Password requirements: minimum 8 characters, uppercase, lowercase, and number
- Current password verification required for password changes

---

## XSS & CSRF Protection

### XSS Prevention
- **All Blade output** uses escaped syntax `{{ $variable }}`
- No unescaped output (`{!! !!}`) found in templates
- Input sanitization removes HTML tags before storage
- Content Security Policy header implemented

### CSRF Protection
- `@csrf` directive present in all POST forms (100% coverage verified)
- `VerifyCsrfToken` middleware active globally
- Token validation automatic for all state-changing requests

---

## File Upload Security

### Upload Validation
```php
'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:max_width=4096,max_height=4096'
```

### Security Measures
1. **MIME type validation**: Only images allowed
2. **File size limits**: Maximum 2MB
3. **Dimension limits**: Maximum 4096x4096 pixels
4. **Filename sanitization**: Uses `uniqid()` to prevent directory traversal
5. **Secure storage**: Files stored in `storage/app/public`
6. **Cleanup**: Old avatars deleted when replaced or user deleted

### File Storage Configuration
- Public disk: `storage/app/public`
- Symbolic link required: `php artisan storage:link`
- Files served through `/storage` URL path

---

## Session Management

### Development Settings (.env.example)
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
```

### Production Settings (.env.production.example)
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
```

### Security Features
- **Database storage**: Sessions stored in database table
- **HTTP-only cookies**: Prevents JavaScript access
- **Secure cookies**: HTTPS-only transmission (production)
- **SameSite**: Strict CSRF protection
- **Encryption**: Session data encrypted (production)
- **120-minute timeout**: Automatic logout after inactivity

---

## Security Headers

### Implemented Headers
The `SecurityHeaders` middleware adds the following to all responses:

1. **X-XSS-Protection**: `1; mode=block`
   - Enables browser XSS filtering

2. **X-Content-Type-Options**: `nosniff`
   - Prevents MIME type sniffing

3. **X-Frame-Options**: `SAMEORIGIN`
   - Prevents clickjacking attacks

4. **Strict-Transport-Security** (production only)
   - Forces HTTPS for 1 year
   - Includes subdomains

5. **Content-Security-Policy**
   - Restricts resource loading
   - Prevents inline script execution

6. **Referrer-Policy**: `strict-origin-when-cross-origin`
   - Controls referrer information

7. **Permissions-Policy**
   - Disables unnecessary browser features

---

## Production Deployment Checklist

### Before Deployment

#### Environment Configuration
- [ ] Copy `.env.production.example` to `.env`
- [ ] Generate new `APP_KEY`: `php artisan key:generate`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Update `APP_URL` to production domain
- [ ] Set `SESSION_SECURE_COOKIE=true` (requires HTTPS)
- [ ] Set `SESSION_ENCRYPT=true`
- [ ] Configure database credentials
- [ ] Configure mail server settings
- [ ] Review all credentials are production-ready

#### File Permissions
```bash
chmod 600 .env
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### Dependencies & Optimization
```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
npm run build
```

#### Database
```bash
php artisan migrate --force
php artisan db:seed --force (if needed)
```

#### SSL/HTTPS
- [ ] Obtain SSL certificate (Let's Encrypt recommended)
- [ ] Configure web server for HTTPS
- [ ] Enable HTTPS redirect in web server config
- [ ] Test SSL configuration: https://www.ssllabs.com/ssltest/

#### Web Server Configuration

**Nginx Example:**
```nginx
# Force HTTPS
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    root /path/to/public;

    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;
    
    # Security headers (redundant with middleware but good practice)
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    
    # Hide server information
    server_tokens off;
    
    # ... rest of configuration
}
```

#### Security Verification
- [ ] Run `composer audit` to check for vulnerabilities
- [ ] Verify `.env` is not publicly accessible
- [ ] Test authentication flows
- [ ] Verify role-based permissions
- [ ] Test CSRF protection
- [ ] Verify file upload restrictions
- [ ] Check security headers: https://securityheaders.com/
- [ ] Scan for vulnerabilities: https://observatory.mozilla.org/

---

## Security Best Practices

### Development
1. Never commit `.env` file to version control
2. Use unique, strong passwords for all services
3. Keep dependencies up to date: `composer update`
4. Run security audits regularly: `composer audit`
5. Use Laravel Pint for code quality: `./vendor/bin/pint`
6. Review logs regularly: `storage/logs/laravel.log`

### Code Security
1. Always use FormRequest validation
2. Always use `$this->authorize()` for sensitive operations
3. Use Eloquent ORM, avoid raw queries
4. Sanitize user input before storage
5. Use `{{ }}` for Blade output, never `{!! !!}` for user data
6. Validate file uploads strictly
7. Use prepared statements for any custom queries

### Production Monitoring
1. Monitor failed login attempts
2. Review application logs daily
3. Set up error reporting/monitoring (e.g., Sentry)
4. Regular database backups
5. Keep Laravel and dependencies updated
6. Monitor server resources
7. Set up uptime monitoring

### User Management
1. Enforce strong password policies
2. Implement account lockout after failed attempts (future enhancement)
3. Log and monitor privileged actions
4. Regular user access reviews
5. Remove inactive accounts

### Data Protection
1. Encrypt sensitive data at rest (consider Laravel's encryption)
2. Use HTTPS for all production traffic
3. Regular security audits
4. Backup strategy with encrypted backups
5. GDPR/data privacy compliance as applicable

---

## Vulnerability Response

### If Security Issue Discovered
1. **Assess severity** and impact
2. **Document** the vulnerability
3. **Patch** immediately if critical
4. **Test** the fix thoroughly
5. **Deploy** to production
6. **Notify** affected users if data breach
7. **Review** and improve security measures

### Reporting Security Issues
Contact: [security@yourdomain.com]
- Include detailed description
- Steps to reproduce
- Potential impact assessment

---

## Regular Maintenance

### Weekly
- Review application logs
- Check failed login attempts
- Verify backups are running

### Monthly
- Run `composer audit`
- Review user access levels
- Update dependencies: `composer update`
- Security scan of production site

### Quarterly
- Full security audit
- Penetration testing (if resources allow)
- Review and update security policies
- Security training for development team

---

## Additional Resources

- [Laravel Security Documentation](https://laravel.com/docs/security)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Best Practices](https://www.php.net/manual/en/security.php)
- [Mozilla Web Security Guidelines](https://infosec.mozilla.org/guidelines/web_security)

---

## Version History
- **v1.0** - Initial security implementation (2024)
  - FormRequest validation
  - Authorization policies
  - Security headers
  - Production configuration
  - Comprehensive documentation
