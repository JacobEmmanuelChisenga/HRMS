<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentCategory;
use Illuminate\Validation\Rule;

class DocumentCategoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $categories = DocumentCategory::where('company_id', $user->company_id)
            ->withCount('documents')
            ->orderBy('name')
            ->paginate(15);

        return view('documents.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('documents.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('document_categories')->where(function ($query) {
                    return $query->where('company_id', auth()->user()->company_id);
                }),
            ],
            'description' => 'nullable|string|max:1000',
            'requires_expiry' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        DocumentCategory::create([
            'company_id' => auth()->user()->company_id,
            'name' => $request->name,
            'description' => $request->description,
            'requires_expiry' => $request->has('requires_expiry'),
            'status' => $request->status,
        ]);

        return redirect()->route('documents.categories.index')->with('success', 'Category created successfully');
    }

    public function edit(DocumentCategory $category)
    {
        // Verify category belongs to same company
        if ($category->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized');
        }

        return view('documents.categories.edit', compact('category'));
    }

    public function update(Request $request, DocumentCategory $category)
    {
        // Verify category belongs to same company
        if ($category->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('document_categories')->where(function ($query) {
                    return $query->where('company_id', auth()->user()->company_id);
                })->ignore($category->id),
            ],
            'description' => 'nullable|string|max:1000',
            'requires_expiry' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'requires_expiry' => $request->has('requires_expiry'),
            'status' => $request->status,
        ]);

        return redirect()->route('documents.categories.index')->with('success', 'Category updated successfully');
    }

    public function destroy(DocumentCategory $category)
    {
        // Verify category belongs to same company
        if ($category->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized');
        }

        // Check if category has documents
        if ($category->documents()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with existing documents');
        }

        $category->delete();

        return redirect()->route('documents.categories.index')->with('success', 'Category deleted successfully');
    }
}
