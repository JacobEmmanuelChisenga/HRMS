<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Holiday;
use Carbon\Carbon;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::where('company_id', auth()->user()->company_id)
            ->orderBy('date')
            ->paginate(15);
        return view('holidays.index', compact('holidays'));
    }

    public function create()
    {
        return view('holidays.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'is_recurring' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ]);

        Holiday::create([
            'company_id' => auth()->user()->company_id,
            'name' => $request->name,
            'date' => $request->date,
            'is_recurring' => $request->has('is_recurring'),
            'description' => $request->description,
        ]);

        return redirect()->route('holidays.index')->with('success', 'Holiday created successfully');
    }

    public function show(Holiday $holiday)
    {
        return view('holidays.show', compact('holiday'));
    }

    public function edit(Holiday $holiday)
    {
        return view('holidays.edit', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'is_recurring' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ]);

        $holiday->update($request->only(['name', 'date', 'description']) + [
            'is_recurring' => $request->has('is_recurring'),
        ]);

        return redirect()->route('holidays.index')->with('success', 'Holiday updated successfully');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('holidays.index')->with('success', 'Holiday deleted successfully');
    }

    public function calendar(Request $request)
    {
        $user = auth()->user();
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);

        $holidays = Holiday::where('company_id', $user->company_id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date')
            ->get();

        // Get all holidays for the year for recurring holidays
        $allHolidays = Holiday::where('company_id', $user->company_id)
            ->whereYear('date', $year)
            ->orWhere(function($q) use ($year) {
                $q->where('is_recurring', true)
                  ->whereYear('date', '<=', $year);
            })
            ->get();

        return view('holidays.calendar', compact('holidays', 'allHolidays', 'year', 'month'));
    }
}
