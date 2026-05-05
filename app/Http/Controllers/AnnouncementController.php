<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('publish_date')
                  ->orWhere('publish_date', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', now());
            })
            ->with('postedBy')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'publish_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date', 'after:publish_date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $isDraft = $request->has('save_as_draft') || $request->is_active === false;
        
        Announcement::create([
            'company_id' => auth()->user()->company_id,
            'title' => $request->title,
            'content' => $request->content,
            'posted_by' => auth()->id(),
            'is_active' => !$isDraft,
            'publish_date' => $request->publish_date,
            'expiry_date' => $request->expiry_date,
        ]);

        $message = $isDraft ? 'Announcement saved as draft' : 'Announcement created successfully';
        $redirectRoute = $isDraft ? 'announcements.drafts' : 'announcements.all';

        return redirect()->route($redirectRoute)->with('success', $message);
    }

    public function show(Announcement $announcement)
    {
        // Verify announcement belongs to same company
        if ($announcement->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized');
        }

        $announcement->load('postedBy');
        return view('announcements.show', compact('announcement'));
    }

    // Admin methods
    public function allAnnouncements()
    {
        $announcements = Announcement::where('company_id', auth()->user()->company_id)
            ->with('postedBy')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('announcements.all', compact('announcements'));
    }

    public function scheduledPosts()
    {
        $announcements = Announcement::where('company_id', auth()->user()->company_id)
            ->whereNotNull('publish_date')
            ->where('publish_date', '>', Carbon::today())
            ->where('is_active', true)
            ->with('postedBy')
            ->orderBy('publish_date', 'asc')
            ->paginate(15);

        return view('announcements.scheduled', compact('announcements'));
    }

    public function draftAnnouncements()
    {
        $announcements = Announcement::where('company_id', auth()->user()->company_id)
            ->where('is_active', false)
            ->with('postedBy')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('announcements.drafts', compact('announcements'));
    }

    public function edit(Announcement $announcement)
    {
        // Verify announcement belongs to same company
        if ($announcement->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized');
        }

        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        // Verify announcement belongs to same company
        if ($announcement->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'publish_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date', 'after:publish_date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $isDraft = $request->has('save_as_draft') || $request->is_active === false;

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => !$isDraft,
            'publish_date' => $request->publish_date,
            'expiry_date' => $request->expiry_date,
        ]);

        $message = $isDraft ? 'Announcement saved as draft' : 'Announcement updated successfully';
        $redirectRoute = $isDraft ? 'announcements.drafts' : 'announcements.all';

        return redirect()->route($redirectRoute)->with('success', $message);
    }

    public function destroy(Announcement $announcement)
    {
        // Verify announcement belongs to same company
        if ($announcement->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized');
        }

        $announcement->delete();

        return redirect()->back()->with('success', 'Announcement deleted successfully');
    }
}
