<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'view_dashboard', 'category' => 'dashboard'],
            ['name' => 'View HR Overview', 'slug' => 'view_hr_overview', 'category' => 'dashboard'],
            
            // Employee Management
            ['name' => 'View Employees', 'slug' => 'view_employees', 'category' => 'employees'],
            ['name' => 'Create Employees', 'slug' => 'create_employees', 'category' => 'employees'],
            ['name' => 'Edit Employees', 'slug' => 'edit_employees', 'category' => 'employees'],
            ['name' => 'Delete Employees', 'slug' => 'delete_employees', 'category' => 'employees'],
            ['name' => 'View Employee Directory', 'slug' => 'view_employee_directory', 'category' => 'employees'],
            ['name' => 'View Organizational Chart', 'slug' => 'view_org_chart', 'category' => 'employees'],
            ['name' => 'View Departments', 'slug' => 'view_departments', 'category' => 'employees'],
            ['name' => 'View Positions', 'slug' => 'view_positions', 'category' => 'employees'],
            
            // Leave Management
            ['name' => 'View Leaves', 'slug' => 'view_leaves', 'category' => 'leave'],
            ['name' => 'Create Leave Requests', 'slug' => 'create_leaves', 'category' => 'leave'],
            ['name' => 'Approve Leaves', 'slug' => 'approve_leaves', 'category' => 'leave'],
            ['name' => 'Reject Leaves', 'slug' => 'reject_leaves', 'category' => 'leave'],
            ['name' => 'View Leave Types', 'slug' => 'view_leave_types', 'category' => 'leave'],
            ['name' => 'View Leave Balances', 'slug' => 'view_leave_balances', 'category' => 'leave'],
            ['name' => 'Adjust Leave Balances', 'slug' => 'adjust_leave_balances', 'category' => 'leave'],
            ['name' => 'View Leave Calendar', 'slug' => 'view_leave_calendar', 'category' => 'leave'],
            ['name' => 'View Leave Reports', 'slug' => 'view_leave_reports', 'category' => 'leave'],
            ['name' => 'Export Leave Data', 'slug' => 'export_leave_data', 'category' => 'leave'],
            
            // Attendance
            ['name' => 'View Attendance', 'slug' => 'view_attendance', 'category' => 'attendance'],
            ['name' => 'Manage Attendance', 'slug' => 'manage_attendance', 'category' => 'attendance'],
            ['name' => 'Mark Attendance', 'slug' => 'mark_attendance', 'category' => 'attendance'],
            ['name' => 'Clock In/Out', 'slug' => 'clock_in_out', 'category' => 'attendance'],
            ['name' => 'View QR Code', 'slug' => 'view_qr_code', 'category' => 'attendance'],
            ['name' => 'Edit Attendance Records', 'slug' => 'edit_attendance_records', 'category' => 'attendance'],
            ['name' => 'View Attendance Reports', 'slug' => 'view_attendance_reports', 'category' => 'attendance'],
            ['name' => 'Export Attendance Data', 'slug' => 'export_attendance_data', 'category' => 'attendance'],
            
            // Documents
            ['name' => 'View Documents', 'slug' => 'view_documents', 'category' => 'documents'],
            ['name' => 'Upload Documents', 'slug' => 'upload_documents', 'category' => 'documents'],
            ['name' => 'Delete Documents', 'slug' => 'delete_documents', 'category' => 'documents'],
            ['name' => 'Request Documents', 'slug' => 'request_documents', 'category' => 'documents'],
            ['name' => 'View Document Reports', 'slug' => 'view_document_reports', 'category' => 'documents'],
            
            // Announcements
            ['name' => 'View Announcements', 'slug' => 'view_announcements', 'category' => 'announcements'],
            ['name' => 'Create Announcements', 'slug' => 'create_announcements', 'category' => 'announcements'],
            
            // Reports
            ['name' => 'View Reports', 'slug' => 'view_reports', 'category' => 'reports'],
            ['name' => 'Generate Reports', 'slug' => 'generate_reports', 'category' => 'reports'],
            
            // Notifications
            ['name' => 'View Notifications', 'slug' => 'view_notifications', 'category' => 'notifications'],
            
            // Settings (Company level - HR Manager should NOT have access)
            ['name' => 'Manage Settings', 'slug' => 'manage_settings', 'category' => 'settings'],
            ['name' => 'Manage Company', 'slug' => 'manage_company', 'category' => 'settings'],
            ['name' => 'Manage Leave Types', 'slug' => 'manage_leave_types', 'category' => 'leave'],
            ['name' => 'Delete Roles', 'slug' => 'delete_roles', 'category' => 'settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        // Assign Permissions to Roles
        $superAdmin = Role::where('slug', 'super_admin')->first();
        $companyAdmin = Role::where('slug', 'company_admin')->first();
        $hrManager = Role::where('slug', 'hr_manager')->first();
        $departmentHead = Role::where('slug', 'department_head')->first();
        $teamLead = Role::where('slug', 'team_lead')->first();
        $employee = Role::where('slug', 'employee')->first();
        $auditor = Role::where('slug', 'auditor')->first();
        $itAdmin = Role::where('slug', 'it_admin')->first();

        if ($superAdmin) {
            $superAdmin->permissions()->sync(Permission::pluck('id'));
        }

        // Company Admin: 40 permissions - Full company control (everything except platform management)
        if ($companyAdmin) {
            $companyAdmin->permissions()->sync(Permission::whereIn('slug', [
                // Dashboard (2)
                'view_dashboard', 'view_hr_overview',
                
                // Employees (8) - Full CRUD + views
                'view_employees', 'create_employees', 'edit_employees', 'delete_employees',
                'view_employee_directory', 'view_org_chart', 'view_departments', 'view_positions',
                
                // Leave (11) - Full leave management
                'view_leaves', 'create_leaves', 'approve_leaves', 'reject_leaves',
                'view_leave_types', 'manage_leave_types', 'view_leave_balances', 'adjust_leave_balances',
                'view_leave_calendar', 'view_leave_reports', 'export_leave_data',
                
                // Attendance (8) - Full attendance management
                'view_attendance', 'manage_attendance', 'mark_attendance',
                'view_qr_code', 'edit_attendance_records',
                'view_attendance_reports', 'export_attendance_data',
                
                // Documents (5) - Full document management
                'view_documents', 'upload_documents', 'delete_documents',
                'request_documents', 'view_document_reports',
                
                // Announcements (2)
                'view_announcements', 'create_announcements',
                
                // Reports (2)
                'view_reports', 'generate_reports',
                
                // Notifications (1)
                'view_notifications',
                
                // Settings (3) - Company management
                'manage_settings', 'manage_company', 'delete_roles',
            ])->pluck('id'));
        }

        // HR Manager: 36 permissions - Company-wide scope (all employees, all departments)
        // Cannot create/edit leave types, cannot delete employees permanently
        if ($hrManager) {
            $hrManager->permissions()->sync(Permission::whereIn('slug', [
                // Dashboard
                'view_dashboard', 'view_hr_overview',
                
                // Employee Management (company-wide)
                'view_employees', 'create_employees', 'edit_employees',
                'view_employee_directory', 'view_org_chart',
                'view_departments', 'view_positions',
                
                // Leave Management (company-wide, cannot manage types)
                'view_leaves', 'create_leaves', 'approve_leaves', 'reject_leaves',
                'view_leave_types', 'view_leave_balances', 'adjust_leave_balances',
                'view_leave_calendar', 'view_leave_reports', 'export_leave_data',
                
                // Attendance (company-wide)
                'view_attendance', 'manage_attendance', 'mark_attendance',
                'view_qr_code', 'edit_attendance_records',
                'view_attendance_reports', 'export_attendance_data',
                
                // Documents (company-wide)
                'view_documents', 'upload_documents', 'delete_documents',
                'request_documents', 'view_document_reports',
                
                // Announcements
                'view_announcements', 'create_announcements',
                
                // Reports & Notifications
                'view_reports', 'generate_reports', 'view_notifications',
            ])->pluck('id'));
        }

        // Department Head: 30 permissions - Department-scoped (their department only)
        // Cannot create departments/positions, cannot edit employees, cannot manage company settings
        if ($departmentHead) {
            $departmentHead->permissions()->sync(Permission::whereIn('slug', [
                // Dashboard (department-scoped) - 2
                'view_dashboard', 'view_hr_overview',
                
                // Employee Management (department only) - 3
                'view_employees', 'view_employee_directory', 'view_org_chart',
                
                // Leave Management (department only) - 10
                'view_leaves', 'create_leaves', 'approve_leaves', 'reject_leaves',
                'view_leave_types', 'view_leave_balances', 'adjust_leave_balances',
                'view_leave_calendar', 'view_leave_reports', 'export_leave_data',
                
                // Attendance (department only) - 7
                'view_attendance', 'manage_attendance', 'mark_attendance',
                'view_qr_code', 'edit_attendance_records',
                'view_attendance_reports', 'export_attendance_data',
                
                // Documents (department only) - 3 (View, Upload, Delete)
                'view_documents', 'upload_documents', 'delete_documents',
                
                // Reports (department only) - 2
                'view_reports', 'generate_reports',
                
                // Announcements (department-specific) - 2
                'view_announcements', 'create_announcements',
                
                // Notifications - 1
                'view_notifications',
            ])->pluck('id'));
        }

        // Team Lead: 18 permissions - Team-scoped (direct reports only)
        // Cannot create/edit employees, cannot manage departments/positions
        if ($teamLead) {
            $teamLead->permissions()->sync(Permission::whereIn('slug', [
                // Dashboard (team stats) - 1
                'view_dashboard',
                
                // Employee Management (team only) - 2
                'view_employees', 'view_employee_directory',
                
                // Leave Management (team only) - 4
                'view_leaves', 'approve_leaves', 'reject_leaves',
                'view_leave_balances',
                
                // Attendance (team only) - 5
                'view_attendance', 'manage_attendance', 'mark_attendance',
                'edit_attendance_records', 'view_attendance_reports',
                
                // Documents (team only) - 2
                'view_documents', 'upload_documents',
                
                // Reports (team only) - 2
                'view_reports', 'generate_reports',
                
                // Notifications - 1
                'view_notifications',
                
                // Announcements - 1 (view only for team context)
                'view_announcements',
            ])->pluck('id'));
        }

        // Employee: 5 permissions - Self-service only
        if ($employee) {
            $employee->permissions()->sync(Permission::whereIn('slug', [
                'view_leaves', 'create_leaves',
                'view_attendance', 'clock_in_out',
                'view_documents',
            ])->pluck('id'));
        }

        // Auditor: 15 permissions - Company-wide read-only for compliance/auditing
        if ($auditor) {
            $auditor->permissions()->sync(Permission::whereIn('slug', [
                // Dashboard (read-only)
                'view_dashboard', 'view_hr_overview',
                
                // Employee Management (view only)
                'view_employees', 'view_employee_directory', 'view_org_chart',
                'view_departments', 'view_positions',
                
                // Leave Management (view only)
                'view_leaves', 'view_leave_types', 'view_leave_balances',
                'view_leave_calendar', 'view_leave_reports', 'export_leave_data',
                
                // Attendance (view only)
                'view_attendance', 'view_attendance_reports', 'export_attendance_data',
                
                // Documents (view only)
                'view_documents', 'view_document_reports',
                
                // Reports (view and generate)
                'view_reports', 'generate_reports',
            ])->pluck('id'));
        }

        // IT Admin: 25+ permissions - Technical control and system maintenance
        if ($itAdmin) {
            $itAdmin->permissions()->sync(Permission::whereIn('slug', [
                // Dashboard
                'view_dashboard', 'view_hr_overview',
                
                // Employee Management (view only, cannot create/edit/delete)
                'view_employees', 'view_employee_directory', 'view_org_chart',
                'view_departments', 'view_positions',
                
                // Leave Management (view only, cannot approve/reject)
                'view_leaves', 'view_leave_types', 'view_leave_balances',
                'view_leave_calendar', 'view_leave_reports', 'export_leave_data',
                
                // Attendance (view and manage technical aspects)
                'view_attendance', 'manage_attendance', 'view_qr_code',
                'view_attendance_reports', 'export_attendance_data',
                
                // Documents (view and manage technical aspects)
                'view_documents', 'upload_documents', 'view_document_reports',
                
                // Reports (view and generate)
                'view_reports', 'generate_reports',
                
                // Notifications
                'view_notifications',
                
                // Settings (technical settings only - system integrations, backups, etc.)
                'manage_settings',
            ])->pluck('id'));
        }
    }
}
