<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Leave Report - {{ $year }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4f46e5; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Leave Report - {{ $year }}</h1>
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Employee Number</th>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Days</th>
                <th>Status</th>
                <th>Approved By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaves as $leave)
            <tr>
                <td>{{ $leave->employee->user->first_name }} {{ $leave->employee->user->last_name }}</td>
                <td>{{ $leave->employee->employee_number }}</td>
                <td>{{ $leave->leaveType->name }}</td>
                <td>{{ $leave->start_date->format('Y-m-d') }}</td>
                <td>{{ $leave->end_date->format('Y-m-d') }}</td>
                <td>{{ $leave->days_requested }}</td>
                <td>{{ ucfirst($leave->status) }}</td>
                <td>{{ $leave->approvedBy->first_name ?? 'N/A' }} {{ $leave->approvedBy->last_name ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
