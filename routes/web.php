<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/jobs', [App\Http\Controllers\HomeController::class, 'store'])->name('jobs.save');
Route::get('/jobs/{job}', [App\Http\Controllers\HomeController::class, 'show'])->name('jobs.show');
Route::put('/jobs/{job}', [App\Http\Controllers\HomeController::class, 'update'])->name('jobs.update');
Route::delete('/jobs/{job}', [App\Http\Controllers\HomeController::class, 'archive'])->name('jobs.archive');
Route::get('/get_job_documents/{jobId}', [HomeController::class, 'getJobDocuments'])->name('job.getDocuments');
Route::get('/download-document/{documentId}', [DocumentController::class, 'download'])->name('document.download');
Route::post('/upload-document', [DocumentController::class, 'uploadDocument'])->name('document.upload');
Route::delete('/delete-document/{documentId}', [DocumentController::class, 'deleteDocument'])->name('document.delete');

