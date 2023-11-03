<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $sortableColumns = ['job_title', 'company_name', 'application_date', 'application_deadline', 'status', 'contact_information', 'notes_or_comments'];
        $sortColumn = $request->input('sort', 'job_title');
        $sortDirection = $request->input('direction', 'asc');
        $page = $request->input('page', 1);
        $search = $request->input('search', '');
        // Validate the sorting column and direction

        if (!in_array($sortColumn, $sortableColumns)) {
            $sortColumn = 'job_title'; // Default sorting column
        }
    
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc'; // Default sorting direction
        }
    
        // Perform the search
        $jobs = Job::where('user_id', $user->id)->where(function ($query) use ($search, $sortableColumns) {
            foreach ($sortableColumns as $column) {
                $query->orWhere($column, 'like', '%' . $search . '%');
            }
        })->orderBy($sortColumn, $sortDirection)->paginate(10);
    
        $data = [
            'jobs' => $jobs,
            'sortDirection' => $sortDirection,
            'sortColumn' => $sortColumn,
            'page' => $page,
            'search' => $search, // Pass the search term back to the view
        ];
    
        return view('home', $data);
    }


    public function store(Request $request)
    {
        // Define custom validation rules for the Job model
        $rules = [
            'job_title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'application_date' => 'required|date',
            'application_deadline' => 'required|date|after:application_date',
            'status' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
            'contact_information' => 'string|nullable|max:255',
            'notes_or_comments' => 'string|nullable',
        ];

        // Validate the request data
        $validatedData = $request->validate($rules);

        // Create a new Job instance and set its attributes
        $job = new Job;
        $job->user_id = Auth::user()->id;
        $job->job_title = $validatedData['job_title'];
        $job->company_name = $validatedData['company_name'];
        $job->application_date = $validatedData['application_date'];
        $job->application_deadline = $validatedData['application_deadline'];
        $job->status = $validatedData['status'];
        $job->contact_information = $validatedData['contact_information'];
        $job->notes_or_comments = $validatedData['notes_or_comments'];

        // Save the job to the database
        $job->save();
        // Handle document storage and association with the job
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $document = new Document();
                $document->job_id = $job->id;
        
                // Get the original filename from the uploaded file
                $originalFilename = $file->getClientOriginalName();
        
                // Generate a new filename to avoid overwriting
                $newFilename = $this->getUniqueFilename($originalFilename);
        
                // Store the file with the new filename
                $file->storeAs('documents', $newFilename, 'custom');
        
                // Set the file_path attribute as the new filename
                $document->file_path = $newFilename;
        
                $document->save();
            }
        }

        return response()->json(['message' => 'Job created successfully']);
    }
    public function show(Job $job)
    {
        // You can use route model binding to automatically fetch the job based on its ID.
        return response()->json($job);
    }

    public function update(Request $request, $jobId) {
        // Validate the request data
        $request->validate([
            'job_title' => 'required',
            'company_name' => 'required',
            'application_date' => 'required|date',
            'application_deadline' => 'required|date',
            'status' => 'required',
            'contact_information' => 'required',
            'notes_or_comments' => 'required',
        ]);
    
        // Find the job by its ID
        $job = Job::findOrFail($jobId);
    
        // Update the job details
        $job->job_title = $request->input('job_title');
        $job->company_name = $request->input('company_name');
        $job->application_date = $request->input('application_date');
        $job->application_deadline = $request->input('application_deadline');
        $job->status = $request->input('status');
        $job->contact_information = $request->input('contact_information');
        $job->notes_or_comments = $request->input('notes_or_comments');
    
        $job->save();
    
        // Return a success response
        return response()->json(['message' => 'Job details updated successfully']);
    }

    public function archive(Job $job) {
        $job->delete(); 
        return response()->json(['message' => 'Job archived successfully']);
    }
    private function getUniqueFilename($originalFilename) {
        $extension = pathinfo($originalFilename, PATHINFO_EXTENSION);
        $filename = pathinfo($originalFilename, PATHINFO_FILENAME);
        $count = 1;
    
        // Check if the file with the new filename already exists
        while (Storage::disk('custom')->exists("documents/{$filename}-{$count}.{$extension}")) {
            $count++;
        }
    
        // Create a unique filename with numbering
        return "{$filename}-{$count}.{$extension}";
    }


    public function getJobDocuments($jobId)
    {
        $job = Job::findOrFail($jobId);
        $documents = $job->documents;
        return response()->json(['documents' => $documents]);
    }

}