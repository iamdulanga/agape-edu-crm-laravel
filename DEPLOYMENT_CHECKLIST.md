# Production Deployment Security Checklist

Use this checklist when deploying to production to ensure all security measures are in place.

## Pre-Deployment Configuration

### Environment File
- [ ] Copy `.env.production.example` to `.env`
- [ ] Generate application key: `php artisan key:generate`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Set correct `APP_URL` (https://yourdomain.com)
- [ ] Configure production database credentials
- [ ] Configure mail server settings
- [ ] Set `LOG_LEVEL=error` or `warning`

### Session Security
- [ ] Set `SESSION_ENCRYPT=true`
- [ ] Set `SESSION_SECURE_COOKIE=true` (requires HTTPS)
- [ ] Set `SESSION_HTTP_ONLY=true`
- [ ] Set `SESSION_SAME_SITE=strict`
- [ ] Set appropriate `SESSION_DOMAIN`

### Database
- [ ] Use strong database password
- [ ] Database user has minimal required privileges
- [ ] Database accessible only from application server
- [ ] Regular backup schedule configured

## Server Configuration

### SSL/HTTPS
- [ ] SSL certificate installed (Let's Encrypt recommended)
- [ ] HTTPS configured on web server
- [ ] HTTP to HTTPS redirect enabled
- [ ] Test SSL: https://www.ssllabs.com/ssltest/
- [ ] HSTS header configured (handled by middleware)

### File Permissions
```bash
chmod 600 .env
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```
- [ ] `.env` file permissions: 600
- [ ] Storage directory writable by web server
- [ ] Bootstrap cache writable by web server
- [ ] Public directory accessible to web server

### Web Server
- [ ] Document root points to `/public`
- [ ] `.env` file not publicly accessible
- [ ] `storage/` directory not publicly accessible
- [ ] Server signature/tokens disabled
- [ ] Directory listing disabled
- [ ] Rate limiting configured

## Application Optimization

### Dependency Management
```bash
composer install --no-dev --optimize-autoloader
npm ci --production
npm run build
```
- [ ] Install production dependencies only
- [ ] Optimize autoloader
- [ ] Build frontend assets

### Laravel Optimization
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```
- [ ] Cache configuration
- [ ] Cache routes
- [ ] Cache views
- [ ] Create storage symlink

### Database
```bash
php artisan migrate --force
php artisan db:seed --force  # Only if needed
```
- [ ] Run migrations
- [ ] Verify database connection
- [ ] Create initial admin/owner account

## Security Verification

### Access Control
- [ ] Test authentication system
- [ ] Verify owner role permissions
- [ ] Verify manager role permissions
- [ ] Verify counselor role permissions
- [ ] Test unauthorized access attempts return 403

### Input Validation
- [ ] Test lead creation with invalid data
- [ ] Test lead update with invalid data
- [ ] Test user creation with invalid data
- [ ] Verify XSS attempts are blocked
- [ ] Test file upload restrictions

### CSRF Protection
- [ ] Test form submissions without CSRF token
- [ ] Verify all POST/PUT/DELETE requests require token

### Security Headers
- [ ] Test security headers: https://securityheaders.com/
- [ ] Verify X-Frame-Options present
- [ ] Verify X-Content-Type-Options present
- [ ] Verify Content-Security-Policy present
- [ ] Verify HSTS header present (production only)

### File Uploads
- [ ] Test avatar upload with valid image
- [ ] Test avatar upload with invalid file type
- [ ] Test avatar upload with oversized file
- [ ] Verify uploaded files accessible via /storage

### Dependency Audit
```bash
composer audit
npm audit
```
- [ ] No known vulnerabilities in Composer packages
- [ ] No critical/high vulnerabilities in npm packages
- [ ] Update vulnerable packages if found

## Monitoring & Logging

### Error Tracking
- [ ] Error logging configured
- [ ] Log rotation configured
- [ ] Critical errors alert/notification set up
- [ ] Consider error tracking service (Sentry, Bugsnag)

### Monitoring
- [ ] Uptime monitoring configured
- [ ] Server resource monitoring
- [ ] Failed login attempt monitoring
- [ ] Database backup monitoring

## Backups

### Database Backups
- [ ] Automated daily database backups
- [ ] Backup retention policy defined
- [ ] Backup restoration tested
- [ ] Backups stored securely (encrypted)
- [ ] Off-site backup location

### File Backups
- [ ] Application files backed up
- [ ] User uploads backed up
- [ ] `.env` file backed up securely
- [ ] Backup schedule documented

## Post-Deployment

### Smoke Tests
- [ ] Homepage loads correctly
- [ ] Login functionality works
- [ ] Dashboard loads for each role
- [ ] Lead creation works
- [ ] Lead update works
- [ ] User management works (owner/manager)
- [ ] Export functionality works
- [ ] File uploads work

### Security Scanning
- [ ] Run security headers check
- [ ] Run SSL test
- [ ] Run vulnerability scan (if available)
- [ ] Penetration test (if resources allow)

### Documentation
- [ ] Deployment date recorded
- [ ] Server credentials documented securely
- [ ] Backup procedures documented
- [ ] Incident response plan documented
- [ ] Security contact information updated

## Ongoing Maintenance

### Daily
- [ ] Monitor error logs
- [ ] Check server health

### Weekly
- [ ] Review failed login attempts
- [ ] Verify backups running successfully
- [ ] Check disk space

### Monthly
- [ ] Run `composer audit`
- [ ] Update dependencies: `composer update`
- [ ] Review user access levels
- [ ] Security scan

### Quarterly
- [ ] Full security audit
- [ ] Review and update security policies
- [ ] Security training for team
- [ ] Test backup restoration

## Emergency Procedures

### Security Breach Response
1. Immediately assess the scope
2. Isolate affected systems
3. Change all credentials
4. Review logs for entry point
5. Patch vulnerability
6. Notify affected users
7. Document incident
8. Review and improve security

### System Down
1. Check server status
2. Review error logs
3. Check database connectivity
4. Verify DNS/network
5. Restore from backup if needed
6. Document incident

## Sign-Off

Deployment completed by: ________________  
Date: ________________  
Environment: ________________  

Security verification by: ________________  
Date: ________________  

--- 

## Notes
Use this space to document any deviations from the checklist or additional security measures implemented:

