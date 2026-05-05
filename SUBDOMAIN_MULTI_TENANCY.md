# Subdomain-Based Multi-Tenancy Implementation

## Overview

The HRMS system now supports **subdomain-based multi-tenancy**, where each company gets their own subdomain for accessing the system. This provides better isolation, branding, and user experience.

## Architecture

### Domain Structure

- **Main Platform Domain**: `hrms.com` (or `hrms.test` for local development)
  - Used by **Super Admin** only
  - Access to platform-wide management features
  
- **Company Subdomains**: `{company-subdomain}.hrms.com`
  - Each company has a unique subdomain
  - Example: `acme.hrms.com`, `techcorp.hrms.com`
  - Company users login via their subdomain

### How It Works

1. **Tenant Identification Middleware** (`IdentifyTenant`)
   - Runs on every request
   - Extracts subdomain from the host
   - Identifies the company and sets context
   - If no subdomain → Main platform (Super Admin)

2. **Authentication**
   - Super Admin can only login from main domain
   - Company users can only login from their company subdomain
   - Authentication validates company context

3. **Data Isolation**
   - All queries automatically filter by `company_id`
   - Users can only see data from their company
   - Super Admin sees all companies (platform-wide)

## Implementation Details

### Middleware Stack

```
Request → IdentifyTenant → ScopeDataByRole → Application
```

1. **IdentifyTenant**: Identifies company from subdomain
2. **ScopeDataByRole**: Applies role-based data filtering

### Key Files

- `app/Http/Middleware/IdentifyTenant.php` - Tenant identification
- `app/Http/Middleware/EnsurePlatformAccess.php` - Platform-only access
- `app/Http/Requests/Auth/LoginRequest.php` - Tenant-aware authentication
- `app/Helpers/TenantHelper.php` - Helper functions

### Helper Functions

```php
currentCompany()      // Get current company model
currentCompanyId()    // Get current company ID
isPlatform()          // Check if on main platform
isTenant()            // Check if on company subdomain
```

## Company Creation

When Super Admin creates a company:

1. **Subdomain is Required**
   - Must be unique
   - Format: lowercase letters, numbers, hyphens
   - Cannot start/end with hyphen
   - Reserved words blocked: `www`, `admin`, `api`, etc.

2. **Company Admin Created**
   - Automatically created with Company Admin role
   - Can login via `{subdomain}.hrms.com`

3. **Login URL**
   - Company users: `https://{subdomain}.hrms.com/login`
   - Super Admin: `https://hrms.com/login`

## DNS Configuration

### Production Setup

For production, you need to configure DNS:

1. **Main Domain** (`hrms.com`)
   - A record pointing to your server IP
   - Or CNAME to your hosting provider

2. **Wildcard Subdomain** (`*.hrms.com`)
   - Wildcard DNS record: `*.hrms.com` → same IP
   - Allows any subdomain to resolve to your server

### Local Development

For local development with Laravel Valet or similar:

```bash
# Using Valet
valet link hrms
valet proxy hrms.test *.hrms.test
```

Or configure `/etc/hosts`:
```
127.0.0.1 hrms.test
127.0.0.1 company1.hrms.test
127.0.0.1 company2.hrms.test
```

## Security Features

1. **Subdomain Validation**
   - Reserved subdomains blocked
   - Format validation (RFC compliant)
   - Unique constraint

2. **Authentication Isolation**
   - Super Admin cannot login from company subdomains
   - Company users cannot login from main domain
   - Company users can only access their company's data

3. **Route Protection**
   - Super Admin routes protected with `platform` middleware
   - Company routes automatically scoped by tenant

## Usage Examples

### Creating a Company

```php
// Super Admin creates company via main domain
POST https://hrms.com/companies
{
    "name": "Acme Corporation",
    "subdomain": "acme",  // Required
    "email": "admin@acme.com",
    // ... other fields
}
```

### Company User Login

```php
// User logs in via company subdomain
POST https://acme.hrms.com/login
{
    "email": "user@acme.com",
    "password": "password"
}
```

### Accessing Company Data

```php
// All queries automatically filtered by company
$employees = EmployeeProfile::where('company_id', currentCompanyId())->get();

// Or use helper
$company = currentCompany();
$employees = $company->users;
```

## Migration Notes

### Existing Companies

If you have existing companies without subdomains:

1. Run migration to make subdomain required
2. Update existing companies with subdomains:
   ```php
   Company::whereNull('subdomain')->each(function($company) {
       $company->update([
           'subdomain' => Str::slug($company->name)
       ]);
   });
   ```

### Database Changes

- `companies.subdomain` is now **required** (not nullable)
- Unique constraint ensures no duplicate subdomains

## Testing

### Test Scenarios

1. **Super Admin Login**
   - ✅ Can login from `hrms.com`
   - ❌ Cannot login from `company1.hrms.com`

2. **Company User Login**
   - ✅ Can login from `company1.hrms.com`
   - ❌ Cannot login from `hrms.com`
   - ❌ Cannot login from `company2.hrms.com`

3. **Data Isolation**
   - Company A users see only Company A data
   - Company B users see only Company B data
   - Super Admin sees all companies

## Troubleshooting

### Subdomain Not Resolving

- Check DNS configuration (wildcard record)
- Verify server configuration (Nginx/Apache)
- Check Laravel route caching: `php artisan route:clear`

### Company Not Found Error

- Verify subdomain exists in database
- Check company status is `active`
- Ensure subdomain matches exactly (case-sensitive)

### Authentication Issues

- Verify user's `company_id` matches current tenant
- Check user's role (Super Admin vs Company Admin)
- Ensure correct subdomain is being used

## Future Enhancements

- Custom domain support (CNAME to subdomain)
- Subdomain aliases
- Multi-region tenant routing
- Tenant-specific branding per subdomain
