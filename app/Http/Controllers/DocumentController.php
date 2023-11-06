<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // DocumentController.php
    public function uploadDocument(Request $request)
    {
        // Validate and store the uploaded document

        $document = new Document([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $request->file('file')->store('documents'),
        ]);

        if ($request->has('associate_with_job')) {
            // Associate the document with a job
            $document->job_id = $request->job_id;
        } elseif ($request->has('associate_with_user')) {
            // Associate the document with a user
            $document->user_id = Auth::user()->id;
        }
        $document->save();

        // Redirect or return a response
    }

    public function download($documentId)
    {
        // Retrieve the document information from the database
        $document = Document::find($documentId);

        if (!$document) {
            abort(404); // Handle not found document
        }

        // Define the file path relative to storage/app/public
        $filePath = 'public/' . $document->file_path;

        // Check if the file exists
        if (Storage::disk('local')->exists($filePath)) {
            return Storage::disk('local')->download($filePath);
        } else {
            abort(404); // Handle file not found
        }
    }

    



}
