# Company Deletion - Cascading Delete Implementation

## Overview
When a company is deleted, **all related data** is automatically removed from the system. This includes database records and associated files.

## Database Cascade Delete

The following tables have `onDelete('cascade')` foreign key constraints on `company_id`, ensuring automatic deletion:

### Direct Company Relationships (company_id)
1. **users** - All users belonging to the company
2. **departments** - All departments in the company
3. **positions** - All positions in the company
4. **leave_types** - All leave types configured for the company
5. **attendance_settings** - Company attendance configuration
6. **qr_codes** - All QR codes for the company
7. **documents** - All documents uploaded to the company
8. **document_categories** - All document categories
9. **holidays** - All company holidays
10. **announcements** - All company announcements
11. **company_settings** - All company-specific settings
12. **audit_trails** - All audit trail records
13. **activity_logs** - All activity log records
14. **reports** - All generated reports
15. **roles** - Company-specific roles (if any)

### Indirect Relationships (Cascade through Users)
When users are deleted, the following related data is also deleted:

1. **employee_profiles** - Employee profile data (cascade from user_id)
2. **leave_balances** - Leave balance records (cascade from employee_id)
3. **leave_requests** - Leave request records (cascade from employee_id)
4. **leave_approvals** - Leave approval records (cascade from leave_request_id and approver_id)
5. **attendance_records** - Attendance records (cascade from employee_id)
6. **attendance_logs** - Attendance log entries (cascade from attendance_record_id)
7. **emergency_contacts** - Emergency contact information (cascade from employee_id)
8. **notifications** - User notifications (cascade from user_id)
9. **document_versions** - Document version history (cascade from document_id and uploaded_by)
10. **document_access_logs** - Document access logs (cascade from document_id and user_id)

## File Deletion

The `Company` model's `boot()` method handles file deletion before database records are removed:

### Files Deleted:
1. **Company Logo** - `storage/app/public/{logo_path}`
2. **Document Files** - All document files and their versions
3. **User Profile Photos** - All user profile pictures
4. **Leave Request Supporting Documents** - All uploaded supporting documents for leave requests

## Implementation Details

### Company Model (`app/Models/Company.php`)
- Added `boot()` method with `deleting` event listener
- Eager loads relationships to avoid N+1 queries
- Deletes all associated files using Laravel Storage

### CompanyController (`app/Http/Controllers/CompanyController.php`)
- Uses `forceDelete()` to permanently remove the company (bypasses soft delete)
- Ensures complete removal of all data

## Deletion Flow

```
1. User clicks "Delete Company" in Super Admin panel
2. CompanyController@destroy is called
3. Company model's deleting event fires:
   - Eager loads: documents.versions, users.employeeProfile.leaveRequests
   - Deletes company logo file
   - Deletes all document files and versions
   - Deletes all user profile photos
   - Deletes all leave request supporting documents
4. forceDelete() is called on Company model
5. Database foreign keys cascade:
   - Company → Users (cascade)
   - Company → Departments (cascade)
   - Company → Positions (cascade)
   - Company → Documents (cascade)
   - Company → Leave Types (cascade)
   - Company → All other direct relationships (cascade)
   - Users → Employee Profiles (cascade)
   - Employee Profiles → Leave Requests (cascade)
   - Employee Profiles → Attendance Records (cascade)
   - Employee Profiles → Emergency Contacts (cascade)
   - Documents → Document Versions (cascade)
   - And all other indirect relationships...
6. Company and all related data is permanently removed
```

## Important Notes

⚠️ **Warning**: Company deletion is **permanent** and **irreversible**. All data will be lost.

✅ **Safe**: The cascade delete ensures no orphaned records remain in the database.

✅ **Complete**: Both database records and physical files are removed.

## Testing

To test company deletion:
1. Create a test company with sample data
2. Add users, departments, documents, leave requests, etc.
3. Delete the company
4. Verify:
   - Company record is removed
   - All users are removed
   - All departments, positions, documents are removed
   - All files are deleted from storage
   - No orphaned records remain
