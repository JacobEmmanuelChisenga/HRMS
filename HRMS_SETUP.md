# HRMS System - Complete Setup Guide

## ✅ Completed Features

### 1. Database Structure
- ✅ 27 database migrations created
- ✅ All models with relationships
- ✅ Seeders for roles, permissions, companies, and users

### 2. Authentication & Authorization
- ✅ Multi-tenant company system
- ✅ Role-based access control (5 roles: Super Admin, Company Admin, HR Manager, Manager, Employee)
- ✅ Permission-based middleware
- ✅ User authentication with company isolation

### 3. Employee Management
- ✅ Employee CRUD operations
- ✅ Employee profiles with personal and employment information
- ✅ Department and Position management
- ✅ Emergency contacts
- ✅ Employee number generation

### 4. Leave Management
- ✅ Leave type configuration
- ✅ Leave balance tracking
- ✅ Leave request submission
- ✅ Leave approval workflow
- ✅ Leave balance calculations
- ✅ Supporting document uploads

### 5. Attendance System
- ✅ QR code-based attendance
- ✅ Clock in/out functionality
- ✅ Attendance records tracking
- ✅ Automatic late detection
- ✅ Hours worked calculation
- ✅ QR code rotation (5-minute intervals)

### 6. Document Management
- ✅ Document categories
- ✅ File uploads (PDF, DOC, DOCX, XLS, XLSX, JPG, PNG)
- ✅ Document versioning
- ✅ Access logging
- ✅ Private/public documents
- ✅ Expiry date tracking

### 7. User Interface
- ✅ Modern, responsive design with Tailwind CSS
- ✅ Role-based sidebars (different navigation for each role)
- ✅ Dashboard with role-specific statistics
- ✅ Beautiful, intuitive UI components

## 🚀 Setup Instructions

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Environment Configuration
Copy `.env.example` to `.env` and configure:
- Database connection
- App URL
- Mail settings (if needed)

### 3. Generate Application Key
```bash
php artisan key:generate
```

### 4. Run Migrations and Seeders
```bash
php artisan migrate
php artisan db:seed
```

This will create:
- Default roles (Super Admin, Company Admin, HR Manager, Manager, Employee)
- Permissions and role-permission mappings
- Demo company with departments, positions, and users

### 5. Create Storage Link
```bash
php artisan storage:link
```

### 6. Build Assets
```bash
npm run build
# or for development
npm run dev
```

### 7. Start Development Server
```bash
php artisan serve
```

## 👥 Demo Users

After running seeders, you can login with:

1. **Company Admin**
   - Email: `admin@democompany.com`
   - Password: `password`

2. **HR Manager**
   - Email: `hr@democompany.com`
   - Password: `password`

3. **IT Manager**
   - Email: `itmanager@democompany.com`
   - Password: `password`

4. **Employee 1**
   - Email: `john.doe@democompany.com`
   - Password: `password`

5. **Employee 2**
   - Email: `jane.smith@democompany.com`
   - Password: `password`

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── EmployeeController.php
│   │   ├── LeaveController.php
│   │   ├── AttendanceController.php
│   │   └── DocumentController.php
│   ├── Middleware/
│   │   ├── CheckRole.php
│   │   └── CheckPermission.php
│   └── Requests/
│       ├── StoreEmployeeRequest.php
│       ├── StoreLeaveRequest.php
│       └── StoreDocumentRequest.php
├── Models/
│   ├── User.php
│   ├── Company.php
│   ├── Role.php
│   ├── Permission.php
│   ├── EmployeeProfile.php
│   ├── LeaveRequest.php
│   ├── AttendanceRecord.php
│   ├── Document.php
│   └── ... (all other models)

resources/
└── views/
    ├── layouts/
    │   └── main.blade.php
    ├── components/
    │   ├── sidebar.blade.php
    │   ├── top-nav.blade.php
    │   └── sidebars/
    │       ├── super_admin.blade.php
    │       ├── company_admin.blade.php
    │       ├── hr_manager.blade.php
    │       ├── manager.blade.php
    │       └── employee.blade.php
    ├── employees/
    ├── leaves/
    ├── attendance/
    └── documents/
```

## 🔐 Role Permissions

### Super Admin
- Full system access
- Manage all companies
- Manage all users
- System settings

### Company Admin
- Full company access
- Manage employees
- Manage departments/positions
- Approve leaves
- View all reports
- Company settings

### HR Manager
- Manage employees
- Approve leaves
- View attendance
- Manage documents
- Generate reports

### Manager
- View team members
- Approve team leaves
- View team attendance
- Generate team reports

### Employee
- View own profile
- Request leaves
- View own leaves
- Clock in/out
- View own attendance
- View own documents
- Upload documents

## 📝 Key Features

### Leave Management
- Automatic leave balance calculation
- Leave type configuration per company
- Multi-level approval workflow
- Leave carry-forward support
- Supporting document uploads

### Attendance
- QR code generation with rotation
- Automatic late detection
- Hours worked calculation
- Attendance history
- IP address tracking

### Documents
- Secure file storage
- Document versioning
- Access logging
- Expiry date tracking
- Category-based organization

## 🛠️ Technologies Used

- **Backend**: Laravel 12
- **Frontend**: Blade Templates + Tailwind CSS
- **JavaScript**: Alpine.js
- **QR Codes**: SimpleSoftwareIO/simple-qrcode
- **Database**: MySQL/PostgreSQL/SQLite

## 📌 Next Steps (Optional Enhancements)

1. Email notifications for leave approvals
2. Mobile app for QR code scanning
3. Advanced reporting and analytics
4. Payroll integration
5. Performance reviews
6. Training management
7. Asset management
8. Multi-language support

## 🐛 Troubleshooting

### Storage Link Issues
If file uploads don't work, ensure storage link exists:
```bash
php artisan storage:link
```

### QR Code Not Displaying
Ensure the QR code package is installed:
```bash
composer require simplesoftwareio/simple-qrcode
```

### Permission Issues
Check that:
- Storage directory is writable
- File permissions are correct
- .env file is configured properly

## 📄 License

This project is proprietary software.

---

**Built with ❤️ for efficient Human Resource Management**
