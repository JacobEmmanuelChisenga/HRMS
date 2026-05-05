<!DOCTYPE html>
<html>
<head>
    <title>Attendance Export</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Attendance Records Export</h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Employee</th>
                <th>Department</th>
                <th>Position</th>
                <th>Clock In</th>
                <th>Clock Out</th>
                <th>Hours Worked</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $record->date->format('Y-m-d') }}</td>
                <td>{{ $record->employee->user->first_name }} {{ $record->employee->user->last_name }}</td>
                <td>{{ $record->employee->department->name ?? 'N/A' }}</td>
                <td>{{ $record->employee->position->name ?? 'N/A' }}</td>
                <td>{{ $record->clock_in ? Carbon\Carbon::parse($record->clock_in)->format('H:i') : 'N/A' }}</td>
                <td>{{ $record->clock_out ? Carbon\Carbon::parse($record->clock_out)->format('H:i') : 'N/A' }}</td>
                <td>{{ $record->hours_worked ?? '0' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $record->status)) }}</td>
                <td>{{ $record->notes ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
