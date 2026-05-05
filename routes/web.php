<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceSettingController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentCategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\PlatformUserController;
use App\Http\Controllers\SystemReportController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\OrganizationalChartController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

// Landing page route - shows company landing page if on tenant subdomain
Route::get('/', [\App\Http\Controllers\LandingPageController::class, 'show'])->name('welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        Route::patch('/change-password', [ProfileController::class, 'updatePassword'])->name('update-password');
        Route::get('/show', [ProfileController::class, 'show'])->name('show');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Employees - Index route (all authenticated users can view)
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index')->middleware('role:company_admin,department_head,hr_manager,team_lead,it_admin,auditor');
    
    // Employees - Specific routes must come before parameterized routes
    // Employees - Create for Company Admin and HR Manager (specific routes first)
    Route::middleware('role:company_admin,hr_manager')->group(function () {
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    });
    
    // Employees - View routes (all authenticated users)
    Route::middleware('role:company_admin,department_head,hr_manager,team_lead,it_admin,auditor')->group(function () {
        Route::get('/employees/directory', [EmployeeController::class, 'directory'])->name('employees.directory');
        Route::get('/employees/terminated', [EmployeeController::class, 'terminated'])->name('employees.terminated');
    });
    
    // Employees - Edit/Update for Company Admin and HR Manager only
    Route::middleware('role:company_admin,hr_manager')->group(function () {
        Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    });
    
    // Employees - Show route (parameterized route must come last)
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show')->middleware('role:company_admin,department_head,hr_manager,team_lead,it_admin,auditor');
    
    // Employees - Delete only for Company Admin (HR Manager cannot delete)
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy')->middleware('role:company_admin');

    // Leaves
    // Leave Management
    Route::prefix('leaves')->name('leaves.')->group(function () {
        Route::get('/', [LeaveController::class, 'index'])->name('index');
        Route::get('/create', [LeaveController::class, 'create'])->name('create');
        Route::post('/', [LeaveController::class, 'store'])->name('store');
        Route::get('/my-leaves', [LeaveController::class, 'myLeaves'])->name('my-leaves');
        
        // Leave Requests - Admin/Department Head/HR/Team Lead routes
        Route::middleware('role:team_lead,hr_manager,department_head,company_admin')->group(function () {
            Route::get('/pending', [LeaveController::class, 'pending'])->name('pending');
            Route::get('/approved', [LeaveController::class, 'approved'])->name('approved');
            Route::get('/rejected', [LeaveController::class, 'rejected'])->name('rejected');
            Route::get('/calendar', [LeaveController::class, 'calendar'])->name('calendar');
            Route::post('/{leave}/approve', [LeaveController::class, 'approve'])->name('approve');
            Route::post('/{leave}/reject', [LeaveController::class, 'reject'])->name('reject');
        });
    });

    // Leave Types - Create/Edit access for Company Admin only (HR Manager can view only)
    Route::middleware('role:company_admin')->group(function () {
        Route::get('/leave-types', [LeaveTypeController::class, 'index'])->name('leave-types.index');
        Route::get('/leave-types/create', [LeaveTypeController::class, 'create'])->name('leave-types.create');
        Route::post('/leave-types', [LeaveTypeController::class, 'store'])->name('leave-types.store');
        Route::get('/leave-types/{leaveType}', [LeaveTypeController::class, 'show'])->name('leave-types.show');
        Route::get('/leave-types/{leaveType}/edit', [LeaveTypeController::class, 'edit'])->name('leave-types.edit');
        Route::put('/leave-types/{leaveType}', [LeaveTypeController::class, 'update'])->name('leave-types.update');
        Route::get('/leave-types/{leaveType}/policies', [LeaveTypeController::class, 'policies'])->name('leave-types.policies');
        Route::put('/leave-types/{leaveType}/policies', [LeaveTypeController::class, 'updatePolicies'])->name('leave-types.policies.update');
    });
    
    // Leave Types - Delete only for Company Admin (HR Manager cannot delete)
    Route::middleware('role:company_admin')->group(function () {
        Route::delete('/leave-types/{leaveType}', [LeaveTypeController::class, 'destroy'])->name('leave-types.destroy');
    });

    // Leave Balances - Both Company Admin and HR Manager can view and adjust individual balances
    Route::prefix('leave-balances')->name('leave-balances.')->middleware('role:company_admin,hr_manager')->group(function () {
        Route::get('/', [LeaveBalanceController::class, 'index'])->name('index');
        Route::get('/{employee}', [LeaveBalanceController::class, 'show'])->name('show');
        Route::post('/{employee}/adjust', [LeaveBalanceController::class, 'adjust'])->name('adjust');
    });
    
    // Leave Balances - Bulk adjustments only for Company Admin
    Route::middleware('role:company_admin')->group(function () {
        Route::get('/leave-balances/bulk-adjustments', [LeaveBalanceController::class, 'bulkAdjustments'])->name('leave-balances.bulk-adjustments');
        Route::post('/leave-balances/bulk-adjustments', [LeaveBalanceController::class, 'processBulkAdjustments'])->name('leave-balances.bulk-adjustments.process');
    });


    // Attendance
    Route::prefix('attendance')->name('attendance.')->group(function () {
        // Employee routes
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('/my-attendance', [AttendanceController::class, 'myAttendance'])->name('my-attendance');
        Route::post('/clock-in', [AttendanceController::class, 'clockIn'])->name('clock-in');
        Route::post('/clock-out', [AttendanceController::class, 'clockOut'])->name('clock-out');
        Route::get('/qr-code', [AttendanceController::class, 'qrCode'])->name('qr-code');

        // Admin/HR routes - Daily Attendance
        Route::middleware('role:company_admin,hr_manager')->group(function () {
            Route::get('/today', [AttendanceController::class, 'todayAttendance'])->name('today');
            Route::match(['get', 'post'], '/mark', [AttendanceController::class, 'markAttendance'])->name('mark');
            Route::get('/dashboard', [AttendanceController::class, 'attendanceDashboard'])->name('dashboard');

            // QR Code Display - View only for HR Manager, full access for Company Admin
            Route::get('/qr/display', [AttendanceController::class, 'qrCodeDisplay'])->name('qr.display');
        });

        // QR Code Management - Generate and Settings only for Company Admin
        Route::middleware('role:company_admin')->group(function () {
            Route::prefix('qr')->name('qr.')->group(function () {
                // GET route redirects to display (handles accidental GET requests)
                Route::get('/generate', function() {
                    return redirect()->route('attendance.qr.display');
                });
                // POST route for actual generation
                Route::post('/generate', [AttendanceController::class, 'generateQrCode'])->name('generate');
                Route::get('/settings', [AttendanceController::class, 'qrSettings'])->name('settings');
                Route::post('/settings', [AttendanceController::class, 'updateQrSettings'])->name('settings.update');
            });
        });

        // Continue with other attendance routes for both roles
        Route::middleware('role:company_admin,hr_manager')->group(function () {

            // Attendance Records
            Route::prefix('records')->name('records.')->group(function () {
                Route::get('/', [AttendanceController::class, 'viewAllRecords'])->name('index');
                Route::get('/late', [AttendanceController::class, 'lateArrivals'])->name('late');
                Route::get('/absences', [AttendanceController::class, 'absences'])->name('absences');
                Route::get('/{id}/edit', [AttendanceController::class, 'editRecords'])->name('edit');
                Route::put('/{id}', [AttendanceController::class, 'updateRecord'])->name('update');
            });

            // Attendance Reports
            Route::prefix('reports')->name('reports.')->group(function () {
                Route::get('/daily', [AttendanceController::class, 'dailyReport'])->name('daily');
                Route::get('/monthly', [AttendanceController::class, 'monthlyReport'])->name('monthly');
                Route::get('/employee-summary', [AttendanceController::class, 'employeeSummary'])->name('employee-summary');
                Route::get('/export', [AttendanceController::class, 'exportData'])->name('export');
            });

            // Attendance Settings
            Route::prefix('settings')->name('settings.')->group(function () {
                Route::get('/work-hours', [AttendanceSettingController::class, 'workHours'])->name('work-hours');
                Route::post('/work-hours', [AttendanceSettingController::class, 'updateWorkHours'])->name('work-hours.update');
                Route::get('/late-thresholds', [AttendanceSettingController::class, 'lateThresholds'])->name('late-thresholds');
                Route::post('/late-thresholds', [AttendanceSettingController::class, 'updateLateThresholds'])->name('late-thresholds.update');
                Route::get('/geofencing', [AttendanceSettingController::class, 'geofencing'])->name('geofencing');
                Route::post('/geofencing', [AttendanceSettingController::class, 'updateGeofencing'])->name('geofencing.update');
            });
        });
    });

    // Documents
    Route::prefix('documents')->name('documents.')->group(function () {
        // Employee routes
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::get('/my-documents', [DocumentController::class, 'myDocuments'])->name('my-documents');
        Route::get('/create', [DocumentController::class, 'create'])->name('create');
        Route::post('/', [DocumentController::class, 'store'])->name('store');

        // Admin/HR routes - must come before {document} route
        Route::middleware('role:company_admin,hr_manager')->group(function () {
            Route::get('/expiring', [DocumentController::class, 'expiringDocuments'])->name('expiring');
            Route::get('/requests', [DocumentController::class, 'documentRequests'])->name('requests');
            Route::get('/reports', [DocumentController::class, 'documentReports'])->name('reports');

            // Document Categories - Create/Edit for Company Admin and HR Manager
            Route::prefix('categories')->name('categories.')->group(function () {
                Route::get('/', [DocumentCategoryController::class, 'index'])->name('index');
                Route::get('/create', [DocumentCategoryController::class, 'create'])->name('create');
                Route::post('/', [DocumentCategoryController::class, 'store'])->name('store');
                Route::get('/{category}/edit', [DocumentCategoryController::class, 'edit'])->name('edit');
                Route::put('/{category}', [DocumentCategoryController::class, 'update'])->name('update');
            });
            
            // Document Categories - Delete only for Company Admin (HR Manager cannot delete)
            Route::prefix('categories')->name('categories.')->middleware('role:company_admin')->group(function () {
                Route::delete('/{category}', [DocumentCategoryController::class, 'destroy'])->name('destroy');
            });
        });

        // Document show/download routes - must come after all specific routes
        Route::get('/{document}', [DocumentController::class, 'show'])->name('show');
        Route::get('/{document}/download', [DocumentController::class, 'download'])->name('download');
    });

    // Departments - Specific routes must come before parameterized routes
    Route::get('/departments/heads', [DepartmentController::class, 'heads'])->name('departments.heads')->middleware('role:company_admin');
    
    // Departments - Index route (all authenticated users can view)
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index')->middleware('role:company_admin,department_head,hr_manager,team_lead,employee,it_admin,auditor');
    
    // Departments - Create/Edit access for Company Admin and HR Manager (specific routes first)
    Route::middleware('role:company_admin,hr_manager')->group(function () {
        Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
        Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
        Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
        Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    });
    
    // Departments - Show route (parameterized route must come last)
    Route::get('/departments/{department}', [DepartmentController::class, 'show'])->name('departments.show')->middleware('role:company_admin,department_head,hr_manager,team_lead,employee,it_admin,auditor');
    
    // Departments - Delete only for Company Admin (HR Manager cannot delete)
    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy')->middleware('role:company_admin');

    // Positions - Specific routes must come before parameterized routes
    // Positions - Index route (all authenticated users can view)
    Route::get('/positions', [PositionController::class, 'index'])->name('positions.index')->middleware('role:company_admin,department_head,hr_manager,team_lead,employee,it_admin,auditor');
    
    // Positions - Create/Edit access for Company Admin and HR Manager (specific routes first)
    Route::middleware('role:company_admin,hr_manager')->group(function () {
        Route::get('/positions/create', [PositionController::class, 'create'])->name('positions.create');
        Route::post('/positions', [PositionController::class, 'store'])->name('positions.store');
        Route::get('/positions/{position}/edit', [PositionController::class, 'edit'])->name('positions.edit');
        Route::put('/positions/{position}', [PositionController::class, 'update'])->name('positions.update');
    });
    
    // Positions - Show route (parameterized route must come last)
    Route::get('/positions/{position}', [PositionController::class, 'show'])->name('positions.show')->middleware('role:company_admin,department_head,hr_manager,team_lead,employee,it_admin,auditor');
    
    // Positions - Delete only for Company Admin (HR Manager cannot delete)
    Route::delete('/positions/{position}', [PositionController::class, 'destroy'])->name('positions.destroy')->middleware('role:company_admin');

    // Organizational Chart
    Route::get('/organizational-chart', [OrganizationalChartController::class, 'index'])->name('organizational-chart.index')->middleware('role:company_admin,department_head,hr_manager,it_admin,auditor');

    // Holidays
    Route::prefix('holidays')->name('holidays.')->middleware('role:company_admin,hr_manager')->group(function () {
        Route::get('/', [HolidayController::class, 'index'])->name('index');
        Route::get('/create', [HolidayController::class, 'create'])->name('create');
        Route::post('/', [HolidayController::class, 'store'])->name('store');
        Route::get('/calendar', [HolidayController::class, 'calendar'])->name('calendar');
        Route::get('/{holiday}', [HolidayController::class, 'show'])->name('show');
        Route::get('/{holiday}/edit', [HolidayController::class, 'edit'])->name('edit');
        Route::put('/{holiday}', [HolidayController::class, 'update'])->name('update');
        Route::delete('/{holiday}', [HolidayController::class, 'destroy'])->name('destroy');
    });

    // Announcements
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');

    // Admin/HR routes - must come before {announcement} route
    Route::middleware('role:company_admin,hr_manager')->prefix('announcements')->name('announcements.')->group(function () {
        Route::get('/all', [AnnouncementController::class, 'allAnnouncements'])->name('all');
        Route::get('/create', [AnnouncementController::class, 'create'])->name('create');
        Route::post('/', [AnnouncementController::class, 'store'])->name('store');
        Route::get('/scheduled', [AnnouncementController::class, 'scheduledPosts'])->name('scheduled');
        Route::get('/drafts', [AnnouncementController::class, 'draftAnnouncements'])->name('drafts');
        Route::get('/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('edit');
        Route::put('/{announcement}', [AnnouncementController::class, 'update'])->name('update');
        Route::delete('/{announcement}', [AnnouncementController::class, 'destroy'])->name('destroy');
    });

    // Announcement show route - must come after all specific routes
    Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');

    // Reports & Analytics
    Route::prefix('reports')->name('reports.')->middleware('role:company_admin,hr_manager')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        
        // Employee Reports
        Route::prefix('employees')->name('employees.')->group(function () {
            Route::get('/list', [ReportController::class, 'employeeList'])->name('list');
            Route::get('/headcount', [ReportController::class, 'headcountAnalysis'])->name('headcount');
            Route::get('/demographics', [ReportController::class, 'demographics'])->name('demographics');
        });

        // Attendance Analytics
        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/trends', [ReportController::class, 'attendanceTrends'])->name('trends');
            Route::get('/department-comparison', [ReportController::class, 'departmentComparison'])->name('department-comparison');
            Route::get('/time-analysis', [ReportController::class, 'timeAnalysis'])->name('time-analysis');
        });

        // Leave Analytics
        Route::prefix('leaves')->name('leaves.')->group(function () {
            Route::get('/summary', [ReportController::class, 'leaveSummary'])->name('summary');
            Route::get('/detailed', [ReportController::class, 'leaveDetailed'])->name('detailed');
            Route::get('/export', [ReportController::class, 'leaveExport'])->name('export');
            Route::get('/utilization', [ReportController::class, 'leaveUtilization'])->name('utilization');
            Route::get('/trends', [ReportController::class, 'leaveTrends'])->name('trends');
            Route::get('/cost-analysis', [ReportController::class, 'leaveCostAnalysis'])->name('cost-analysis');
        });

        // Document Reports
        Route::prefix('documents')->name('documents.')->group(function () {
            Route::get('/inventory', [ReportController::class, 'documentInventory'])->name('inventory');
            Route::get('/compliance', [ReportController::class, 'complianceStatus'])->name('compliance');
        });

        // Custom Reports
        Route::prefix('custom')->name('custom.')->group(function () {
            Route::get('/builder', [ReportController::class, 'reportBuilder'])->name('builder');
            Route::get('/saved', [ReportController::class, 'savedReports'])->name('saved');
            Route::get('/scheduled', [ReportController::class, 'scheduledReports'])->name('scheduled');
        });

        // Export Center
        Route::prefix('exports')->name('exports.')->group(function () {
            Route::get('/excel', [ReportController::class, 'exportToExcel'])->name('excel');
            Route::get('/pdf', [ReportController::class, 'exportToPdf'])->name('pdf');
            Route::get('/bulk', [ReportController::class, 'bulkExports'])->name('bulk');
        });
    });

    // Audit & Compliance
    Route::prefix('audit')->name('audit.')->middleware('role:company_admin,hr_manager')->group(function () {
        Route::get('/', [AuditController::class, 'index'])->name('index');
        
        // Audit Trails
        Route::prefix('activities')->name('activities.')->group(function () {
            Route::get('/', [AuditController::class, 'allActivities'])->name('index');
            Route::get('/user-actions', [AuditController::class, 'userActions'])->name('user-actions');
            Route::get('/data-changes', [AuditController::class, 'dataChanges'])->name('data-changes');
        });

        // Document Access Logs
        Route::prefix('documents')->name('documents.')->group(function () {
            Route::get('/access-logs', [AuditController::class, 'documentAccessLogs'])->name('access-logs');
        });

        // Attendance Modifications
        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/modifications', [AuditController::class, 'attendanceModifications'])->name('modifications');
        });

        // Compliance Reports
        Route::prefix('compliance')->name('compliance.')->group(function () {
            Route::get('/reports', [AuditController::class, 'complianceReports'])->name('reports');
        });
    });

    // Company Settings
    Route::prefix('settings')->name('settings.')->middleware('role:company_admin,it_admin')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        
        // Company Profile
        Route::prefix('company')->name('company.')->group(function () {
            Route::get('/basic-information', [SettingController::class, 'basicInformation'])->name('basic-information');
            Route::post('/basic-information', [SettingController::class, 'updateBasicInformation'])->name('basic-information.update');
            Route::get('/branding', [SettingController::class, 'branding'])->name('branding');
            Route::post('/branding', [SettingController::class, 'updateBranding'])->name('branding.update');
        });

        // Landing Page (Company Admin only)
        Route::middleware('role:company_admin')->group(function () {
            Route::get('/landing-page', [\App\Http\Controllers\LandingPageController::class, 'edit'])->name('landing-page.edit');
            Route::put('/landing-page', [\App\Http\Controllers\LandingPageController::class, 'update'])->name('landing-page.update');
        });

        // Auth Pages (Login/Registration) (Company Admin only)
        Route::middleware('role:company_admin')->group(function () {
            Route::get('/auth-pages', [\App\Http\Controllers\AuthPageController::class, 'edit'])->name('auth-pages.edit');
            Route::put('/auth-pages', [\App\Http\Controllers\AuthPageController::class, 'update'])->name('auth-pages.update');
        });

        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [SettingController::class, 'allUsers'])->name('index');
            Route::get('/create', [SettingController::class, 'createUser'])->name('create');
            Route::post('/', [SettingController::class, 'storeUser'])->name('store');
            Route::get('/{user}/edit', [SettingController::class, 'editUser'])->name('edit');
            Route::put('/{user}', [SettingController::class, 'updateUser'])->name('update');
            Route::get('/roles-permissions', [SettingController::class, 'rolesAndPermissions'])->name('roles-permissions');
        });
        
        // Role Management (Company Admin can create custom roles)
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::get('/create', [RoleController::class, 'create'])->name('create');
            Route::post('/', [RoleController::class, 'store'])->name('store');
            Route::get('/{role}', [RoleController::class, 'show'])->name('show');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
            Route::put('/{role}', [RoleController::class, 'update'])->name('update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
        });

        // Subscription
        Route::prefix('subscription')->name('subscription.')->group(function () {
            Route::get('/current-plan', [SettingController::class, 'currentPlan'])->name('current-plan');
            Route::get('/billing-history', [SettingController::class, 'billingHistory'])->name('billing-history');
            Route::get('/upgrade-plan', [SettingController::class, 'upgradePlan'])->name('upgrade-plan');
        });
        
        // IT Admin specific routes
        Route::middleware('role:it_admin')->group(function () {
            Route::get('/integrations', [SettingController::class, 'integrations'])->name('integrations');
            Route::get('/backups', [SettingController::class, 'backups'])->name('backups');
            Route::get('/logs', [SettingController::class, 'logs'])->name('logs');
        });
    });

    // Super Admin Routes - Only accessible from main platform domain
    Route::middleware(['role:super_admin', 'platform'])->group(function () {
        // Companies Management
        Route::resource('companies', CompanyController::class);
        Route::get('/companies-settings', [CompanyController::class, 'settings'])->name('companies.settings');
        
        // Company Landing Pages (Super Admin can manage any company's landing page)
        Route::get('/companies/{company}/landing-page', [\App\Http\Controllers\LandingPageController::class, 'edit'])->name('super-admin.companies.landing-page');
        Route::put('/companies/{company}/landing-page', [\App\Http\Controllers\LandingPageController::class, 'update'])->name('super-admin.companies.landing-page.update');
        
        // Company Auth Pages (Login/Registration) (Super Admin can manage any company's auth pages)
        Route::get('/companies/{company}/auth-pages', [\App\Http\Controllers\AuthPageController::class, 'edit'])->name('super-admin.companies.auth-pages');
        Route::put('/companies/{company}/auth-pages', [\App\Http\Controllers\AuthPageController::class, 'update'])->name('super-admin.companies.auth-pages.update');

        // Subscriptions
        Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::patch('/subscriptions/{company}', [SubscriptionController::class, 'update'])->name('subscriptions.update');

        // Billing
        Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');

        // Platform Users
        Route::get('/platform-users', [PlatformUserController::class, 'index'])->name('platform-users.index');
        Route::get('/platform-users/super-admins', [PlatformUserController::class, 'superAdmins'])->name('platform-users.super-admins');

        // System Reports
        Route::prefix('system-reports')->name('system-reports.')->group(function () {
            Route::get('/analytics', [SystemReportController::class, 'analytics'])->name('analytics');
            Route::get('/usage', [SystemReportController::class, 'usage'])->name('usage');
            Route::get('/performance', [SystemReportController::class, 'performance'])->name('performance');
        });

        // System Settings
        Route::prefix('system-settings')->name('system-settings.')->group(function () {
            Route::get('/general', [SystemSettingController::class, 'general'])->name('general');
            Route::post('/general', [SystemSettingController::class, 'updateGeneral'])->name('general.update');
            Route::get('/email', [SystemSettingController::class, 'email'])->name('email');
            Route::post('/email', [SystemSettingController::class, 'updateEmail'])->name('email.update');
            Route::get('/security', [SystemSettingController::class, 'security'])->name('security');
            Route::post('/security', [SystemSettingController::class, 'updateSecurity'])->name('security.update');
            Route::get('/features', [SystemSettingController::class, 'features'])->name('features');
            Route::post('/features', [SystemSettingController::class, 'updateFeatures'])->name('features.update');
            Route::get('/maintenance', [SystemSettingController::class, 'maintenance'])->name('maintenance');
            Route::post('/maintenance', [SystemSettingController::class, 'toggleMaintenance'])->name('maintenance.toggle');
        });

        // Notifications
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::get('/alerts', [NotificationController::class, 'alerts'])->name('alerts');
        });

        // Activity Logs
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });
});

require __DIR__.'/auth.php';
