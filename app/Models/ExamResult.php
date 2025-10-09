<?php
// app/Models/ExamResult.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ExamResult extends Model
{
    use HasFactory;
    protected $fillable = ['exam_id','student_id','student_degree'];

    public function exam() {
        return $this->belongsTo(Exam::class);
    }

    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }
}

