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
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $document = new Document();

            // Get the original filename from the uploaded file
            $originalFilename = $file->getClientOriginalName();

            // Generate a unique filename to avoid overwriting existing files
            $newFilename = $file->getClientOriginalName(); // Example: timestamp_originalfilename

            // Store the file with the new filename in the storage/app/public directory
            $filePath = $file->storeAs('public/documents', $newFilename, 'local');

            // Set the file_path attribute as the path relative to storage/app/public
            $document->file_path = 'documents/' . $newFilename;

            $document->save();

            if ($request->has('associate_with_job')) {
                // Associate the document with a job
                $document->job_id = $request->job_id;
            } elseif ($request->has('associate_with_user')) {
                // Associate the document with a user
                $document->user_id = Auth::user()->id;
            }

            $document->save();

            // Return a success response or any other desired response
            return response()->json(['message' => 'Document uploaded successfully']);
        }

        // Handle the case where no file was uploaded
        return response()->json(['error' => 'No file was uploaded'], 422);
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
