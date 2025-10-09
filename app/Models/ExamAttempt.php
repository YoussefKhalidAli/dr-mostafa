<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ExamAttempt extends Model
{
    use HasFactory;
    protected $fillable = [
    'exam_id',
    'student_id',
    'started_at',
    'submitted_at',
    'score',
    'ended_at',
    'submitted',
    'auto_submitted',
];

protected $casts = [
    'submitted' => 'boolean',
    'auto_submitted' => 'boolean',
    'started_at' => 'datetime',
    'submitted_at' => 'datetime',
    'ended_at' => 'datetime',
];


    // protected $dates = ['started_at', 'submitted_at'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
