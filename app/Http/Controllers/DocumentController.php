<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\EmployeeProfile;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Traits\ScopesDataByRole;

class DocumentController extends Controller
{
    use ScopesDataByRole;

    public function index()
    {
        $query = Document::query();
        $this->scopeDocuments($query);
        $documents = $query->with(['employee.user', 'category', 'uploadedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('documents.index', compact('documents'));
    }

    public function myDocuments()
    {
        $employee = auth()->user()->employeeProfile;
        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee profile not found');
        }

        $documents = Document::where('employee_id', $employee->id)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('documents.my-documents', compact('documents'));
    }

    public function create()
    {
        $categories = DocumentCategory::where('company_id', auth()->user()->company_id)
            ->where('status', 'active')
            ->get();
        return view('documents.create', compact('categories'));
    }

    public function store(StoreDocumentRequest $request)
    {
        $file = $request->file('document');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->store('documents', 'public');
        $fileSize = $file->getSize();
        $fileType = $file->getMimeType();

        $document = Document::create([
            'company_id' => auth()->user()->company_id,
            'employee_id' => $request->employee_id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'uploaded_by' => auth()->id(),
            'is_private' => $request->has('is_private'),
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('documents.index')->with('success', 'Document uploaded successfully');
    }

    public function show(Document $document)
    {
        // Check access
        if ($document->is_private && $document->employee_id && $document->employee_id !== auth()->user()->employeeProfile?->id) {
            if (!auth()->user()->hasRole('hr_manager') && !auth()->user()->hasRole('company_admin') && !auth()->user()->hasRole('department_head')) {
                abort(403, 'You do not have permission to view this document');
            }
        }

        $document->load(['category', 'uploadedBy', 'employee.user', 'versions']);
        return view('documents.show', compact('document'));
    }

    public function download(Document $document)
    {
        // Check access
        if ($document->is_private && $document->employee_id && $document->employee_id !== auth()->user()->employeeProfile?->id) {
            if (!auth()->user()->hasRole('hr_manager') && !auth()->user()->hasRole('company_admin') && !auth()->user()->hasRole('department_head')) {
                abort(403, 'You do not have permission to download this document');
            }
        }

        if (!Storage::disk('public')->exists($document->file_path)) {
            return redirect()->back()->with('error', 'File not found');
        }

        // Log access
        \App\Models\DocumentAccessLog::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'action' => 'download',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function expiringDocuments(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;
        
        $daysAhead = $request->days ?? 30; // Default to 30 days ahead
        
        $documents = Document::where('company_id', $companyId)
            ->whereNotNull('expires_at')
            ->where('expires_at', '>=', Carbon::today())
            ->where('expires_at', '<=', Carbon::today()->addDays($daysAhead))
            ->with(['employee.user', 'category', 'uploadedBy'])
            ->orderBy('expires_at', 'asc')
            ->paginate(15);

        return view('documents.expiring', compact('documents', 'daysAhead'));
    }

    public function documentRequests(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        // For now, we'll show documents that employees might need to upload
        // This could be enhanced with a proper DocumentRequest model in the future
        $query = Document::where('company_id', $companyId)
            ->with(['employee.user', 'category', 'uploadedBy']);

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('is_verified', false);
            } elseif ($request->status === 'verified') {
                $query->where('is_verified', true);
            }
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get employees who might need to upload documents
        $employees = EmployeeProfile::whereHas('user', function($q) use ($companyId) {
            $q->where('company_id', $companyId)->where('status', 'active');
        })->with('user')->get();

        return view('documents.requests', compact('documents', 'employees'));
    }

    public function documentReports(Request $request)
    {
        // Overall statistics (scoped)
        $baseQuery = Document::query();
        $this->scopeDocuments($baseQuery);
        
        $totalDocuments = (clone $baseQuery)->count();
        $expiringSoon = (clone $baseQuery)
            ->whereNotNull('expires_at')
            ->where('expires_at', '>=', Carbon::today())
            ->where('expires_at', '<=', Carbon::today()->addDays(30))
            ->count();
        $verifiedDocuments = (clone $baseQuery)
            ->where('is_verified', true)
            ->count();
        $unverifiedDocuments = (clone $baseQuery)
            ->where('is_verified', false)
            ->count();

        // Documents by category (scoped)
        $user = auth()->user();
        $companyId = $user->company_id;
        $documentsByCategory = DocumentCategory::where('company_id', $companyId)
            ->withCount(['documents' => function($query) use ($user) {
                $this->scopeDocuments($query);
            }])
            ->orderBy('documents_count', 'desc')
            ->get();

        // Documents by employee (scoped)
        $documentsByEmployeeQuery = Document::query();
        $this->scopeDocuments($documentsByEmployeeQuery);
        $documentsByEmployee = $documentsByEmployeeQuery
            ->whereNotNull('employee_id')
            ->with('employee.user')
            ->selectRaw('employee_id, count(*) as document_count')
            ->groupBy('employee_id')
            ->orderBy('document_count', 'desc')
            ->limit(10)
            ->get();

        // Recent uploads (scoped)
        $recentUploadsQuery = Document::query();
        $this->scopeDocuments($recentUploadsQuery);
        $recentUploads = $recentUploadsQuery
            ->with(['employee.user', 'category', 'uploadedBy'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('documents.reports', compact(
            'totalDocuments',
            'expiringSoon',
            'verifiedDocuments',
            'unverifiedDocuments',
            'documentsByCategory',
            'documentsByEmployee',
            'recentUploads'
        ));
    }
}
