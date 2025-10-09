<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\ExamAttempt;
use App\Models\ExamQuestion;
use App\Models\ExamQuestionOption;
use App\Models\ExamAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamAnswerFactory extends Factory
{
    protected $model = ExamAnswer::class;

    public function definition(): array
    {
        return [
            'student_id' => User::factory(),
            'exam_question_id' => ExamQuestion::factory(),
            'exam_question_option_id' => ExamQuestionOption::factory(),
            'correct_option_id' => null,
            'exam_attempt_id' => ExamAttempt::factory(),
            'degree' => $this->faker->numberBetween(0, 10),
        ];
    }
}
