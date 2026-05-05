<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::where('company_id', auth()->user()->company_id)
            ->with('department')
            ->paginate(15);
        return view('positions.index', compact('positions'));
    }

    public function create()
    {
        $departments = \App\Models\Department::where('company_id', auth()->user()->company_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
        return view('positions.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'department_id' => ['required', 'exists:departments,id'],
            'level' => ['required', 'in:entry,junior,mid,senior,lead,executive'],
        ]);

        Position::create([
            'company_id' => auth()->user()->company_id,
            'title' => $request->title,
            'description' => $request->description,
            'department_id' => $request->department_id,
            'level' => $request->level,
            'status' => 'active',
        ]);

        return redirect()->route('positions.index')->with('success', 'Position created successfully');
    }

    public function show(Position $position)
    {
        $position->load('department', 'employees.user');
        return view('positions.show', compact('position'));
    }

    public function edit(Position $position)
    {
        $departments = \App\Models\Department::where('company_id', auth()->user()->company_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
        return view('positions.edit', compact('position', 'departments'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'department_id' => ['required', 'exists:departments,id'],
            'level' => ['required', 'in:entry,junior,mid,senior,lead,executive'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $position->update($request->only(['title', 'description', 'department_id', 'level', 'status']));

        return redirect()->route('positions.index')->with('success', 'Position updated successfully');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->route('positions.index')->with('success', 'Position deleted successfully');
    }
}
