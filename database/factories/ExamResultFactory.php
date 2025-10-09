<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\User;
use App\Models\ExamResult;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamResultFactory extends Factory
{
    protected $model = ExamResult::class;

    public function definition(): array
    {
        return [
            'exam_id' => Exam::factory(),
            'student_id' => User::factory(),
            'student_degree' => $this->faker->numberBetween(0, 100),
        ];
    }
}
