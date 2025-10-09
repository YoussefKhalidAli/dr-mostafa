<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\User;
use App\Models\ExamAttempt;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamAttemptFactory extends Factory
{
    protected $model = ExamAttempt::class;

    public function definition(): array
    {
        return [
            'exam_id' => Exam::factory(),
            'student_id' => User::factory(),
            'started_at' => now(),
            'submitted_at' => null,
            'ended_at' => null,
            'score' => $this->faker->numberBetween(0, 100),
            'submitted' => false,
            'auto_submitted' => false,
        ];
    }

    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'submitted' => true,
            'submitted_at' => now(),
            'ended_at' => now(),
        ]);
    }
}
