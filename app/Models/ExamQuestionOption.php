<?php

// app/Models/ExamQuestionOption.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ExamQuestionOption extends Model
{
    use HasFactory;
    protected $fillable = ['exam_question_id','title','is_correct'];

    public function question() {
        return $this->belongsTo(ExamQuestion::class);
    }
}
