<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use SoftDeletes;

    protected $deletedAt = 'deleted_at';
    protected $fillable = [
        'job_title',
        'company_name',
        'application_date',
        'application_deadline',
        'status',
        'contact_information',
        'notes_or_comments',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
