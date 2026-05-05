# Role Hierarchy: Understanding "Company Managers"

## **Role Structure Overview**

Your HRMS system has **6 roles** organized in a hierarchy:

```
1. Super Admin (Platform-wide)
   ↓
2. Company Admin (Company-wide control)
   ↓
3. HR Manager (Company-wide HR operations)
   ↓
4. Department Head (Department-level management)
   ↓
5. Team Lead (Team-level management)
   ↓
6. Employee (Self-service)
```

---

## **Where Do "Company Managers" Fit?**

The term "company manager" is ambiguous. Here's how it maps to your system:

### **Option 1: Team Lead** (Most Common)
**If "company manager" means:**
- A supervisor who manages a small team (3-10 people)
- A manager who manages direct reports only
- Examples: Finance Manager, Sales Manager, IT Manager (managing a team)

**Then use:** ✅ **Team Lead**

**Scope:** Only their direct reports (employees where `manager_id = team_lead.id`)

**Permissions:** 18 permissions
- Can approve leave for their team
- Can manage attendance for their team
- Can view/manage only their direct reports
- Cannot see other teams or departments

---

### **Option 2: Department Head**
**If "company manager" means:**
- A director who manages an entire department
- A department head who oversees all employees in their department
- Examples: Finance Director, Sales Director, IT Director

**Then use:** ✅ **Department Head**

**Scope:** Entire department (all employees in their department)

**Permissions:** 30 permissions
- Can approve leave for entire department
- Can manage attendance for entire department
- Can view/manage all employees in their department
- Cannot see other departments

---

### **Option 3: HR Manager**
**If "company manager" means:**
- HR department head who manages all employees company-wide
- Someone who needs to see/manage employees across all departments

**Then use:** ✅ **HR Manager**

**Scope:** Company-wide (all departments, all employees)

**Permissions:** 36 permissions
- Can create/edit employees
- Can approve leave company-wide
- Can manage attendance company-wide
- Cannot delete employees or manage settings

---

## **Real-World Examples**

### **Scenario 1: Small Company Structure**
```
Company Admin (CEO)
  ├── HR Manager (Manages all employees)
  ├── Finance Director (Department Head)
  │   ├── Finance Manager (Team Lead) → Manages 3 accountants
  │   └── Accountants (Employees)
  └── Sales Director (Department Head)
      ├── Sales Manager (Team Lead) → Manages 5 sales reps
      └── Sales Reps (Employees)
```

### **Scenario 2: Medium Company Structure**
```
Company Admin (CEO)
  ├── HR Manager (Manages all employees)
  ├── IT Department Head (Department Head)
  │   ├── IT Manager (Team Lead) → Manages developers
  │   └── Developers (Employees)
  └── Marketing Department Head (Department Head)
      ├── Marketing Manager (Team Lead) → Manages marketers
      └── Marketers (Employees)
```

---

## **Key Differences**

| Role | Scope | Manages | Typical Title | Permissions |
|------|-------|---------|---------------|-------------|
| **Team Lead** | Direct reports only | 3-10 people | Manager, Supervisor | 18 |
| **Department Head** | Entire department | 10-50+ people | Director, Head | 30 |
| **HR Manager** | Company-wide | All employees | HR Director | 36 |
| **Company Admin** | Company-wide | Everything | CEO, Owner | 41 |

---

## **How to Decide Which Role to Use**

### **Use Team Lead if:**
- ✅ Manager manages 3-10 direct reports
- ✅ Manager doesn't need to see entire department
- ✅ Manager only approves leave for their team
- ✅ Examples: Finance Manager, Sales Manager, IT Manager

### **Use Department Head if:**
- ✅ Manager manages entire department (10-50+ people)
- ✅ Manager needs to see all employees in department
- ✅ Manager approves leave for entire department
- ✅ Examples: Finance Director, Sales Director, IT Director

### **Use HR Manager if:**
- ✅ Manager manages all employees company-wide
- ✅ Manager needs to create/edit employees
- ✅ Manager approves leave for all departments
- ✅ Examples: HR Director, People Operations Manager

---

## **Current System Implementation**

### **Team Lead Scope:**
```php
// Only sees employees where manager_id = team_lead.id
$employees = EmployeeProfile::where('manager_id', $user->id)->get();
```

### **Department Head Scope:**
```php
// Only sees employees in their department
$employees = EmployeeProfile::where('department_id', $user->employee->department_id)->get();
```

### **HR Manager Scope:**
```php
// Sees all employees in company
$employees = EmployeeProfile::whereHas('user', function($q) use ($user) {
    $q->where('company_id', $user->company_id);
})->get();
```

---

## **Answer to Your Question**

**"Team Lead is the category in which company managers fall?"**

**Answer:** It depends on what you mean by "company manager":

1. **If "company manager" = Team/Supervisor Manager** → ✅ **YES, use Team Lead**
   - Manages 3-10 direct reports
   - Only sees their team

2. **If "company manager" = Department Manager** → ❌ **NO, use Department Head**
   - Manages entire department
   - Sees all employees in department

3. **If "company manager" = General Manager** → ❌ **NO, use HR Manager or Company Admin**
   - Manages company-wide operations
   - Needs broader access

---

## **Recommendation**

**Most "company managers" in typical organizations are Team Leads** because:
- They manage a small team (3-10 people)
- They only need to see their direct reports
- They don't need department-wide access

**Use Department Head only if:**
- The manager oversees an entire department
- They need to see all employees in the department
- They're at a director/head level

---

## **Summary**

| Your Term | System Role | When to Use |
|-----------|-------------|-------------|
| Team Manager | **Team Lead** | Manages 3-10 direct reports |
| Department Manager | **Department Head** | Manages entire department |
| HR Manager | **HR Manager** | Manages all employees company-wide |
| Company Manager | **Team Lead** (usually) | Most common use case |

**Bottom line:** Most "company managers" should be assigned the **Team Lead** role, unless they manage an entire department (then use **Department Head**).
