<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->string('company_name'); // Add this line for company name
            $table->date('application_date');
            $table->date('application_deadline');
            $table->string('status');
            $table->string('contact_information');
            $table->text('notes_or_comments')->nullable();
            $table->unsignedBigInteger('user_id'); // Foreign key to link jobs to users
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
