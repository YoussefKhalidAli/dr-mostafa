<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ExamAnswer extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'exam_question_id',
        'exam_question_option_id',
        'correct_option_id',
        'exam_attempt_id', // new
        'degree'
    ];

    public function question() {
        return $this->belongsTo(ExamQuestion::class, 'exam_question_id');
    }

    public function chosenOption() {
        return $this->belongsTo(ExamQuestionOption::class, 'exam_question_option_id');
    }

    public function correctOption() {
        return $this->belongsTo(ExamQuestionOption::class, 'correct_option_id');
    }

    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function attempt() {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }
}
