<?php

namespace Database\Factories;

use App\Models\ExamQuestion;
use App\Models\ExamQuestionOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamQuestionOptionFactory extends Factory
{
    protected $model = ExamQuestionOption::class;

    public function definition(): array
    {
        return [
            'exam_question_id' => ExamQuestion::factory(),
            'title' => $this->faker->sentence(3),
            'is_correct' => $this->faker->boolean(25), // 25% chance to be correct
        ];
    }
}
