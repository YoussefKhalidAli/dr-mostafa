<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Exam;
use App\Models\Lesson;
use App\Models\ExamResult;
use App\Models\ExamQuestion;
use App\Models\ExamQuestionOption;
use App\Models\ExamAnswer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class ExamControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function teacher_can_create_exam()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $lesson = Lesson::factory()->create(['teacher_id' => $teacher->id]);

        $this->actingAs($teacher);

        $response = $this->post(route('exams.store'), [
            'title' => 'Midterm Exam',
            'description' => 'Covers chapters 1–3',
            'lesson_id' => $lesson->id,
            'total_degree' => 100,
            'is_open' => true,
            'is_limited' => false,
        ]);

        $response->assertRedirect(route('exams.index'));
        $this->assertDatabaseHas('exams', ['title' => 'Midterm Exam']);
    }

    /** @test */
    public function teacher_can_add_question_to_exam()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $lesson = Lesson::factory()->create(['teacher_id' => $teacher->id]);
        $exam = Exam::factory()->create(['teacher_id' => $teacher->id, 'lesson_id' => $lesson->id]);

        $this->actingAs($teacher);

        $response = $this->post(route('exams.addQuestion', $exam->id), [
            'title' => 'What is Laravel?',
            'degree' => 10,
            'correct_option' => 1,
            'options' => [
                ['title' => 'A PHP Framework'],
                ['title' => 'A CSS Library'],
            ],
        ]);

        $response->assertRedirect(route('exams.show', $exam->id));

        $this->assertDatabaseHas('exam_questions', [
            'exam_id' => $exam->id,
            'title' => 'What is Laravel?',
        ]);

        $this->assertDatabaseCount('exam_question_options', 2);
    }

    /** @test */
    public function student_can_view_available_exams()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);
        $lesson = Lesson::factory()->create(['teacher_id' => $teacher->id]);
        $exam = Exam::factory()->create(['lesson_id' => $lesson->id, 'teacher_id' => $teacher->id]);

        $this->actingAs($student);

        $response = $this->get(route('student.exams.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function unauthorized_teacher_cannot_edit_another_teacher_exam()
    {
        $teacher1 = User::factory()->create(['role' => 'teacher']);
        $teacher2 = User::factory()->create(['role' => 'teacher']);
        $lesson = Lesson::factory()->create(['teacher_id' => $teacher1->id]);
        $exam = Exam::factory()->create(['teacher_id' => $teacher1->id, 'lesson_id' => $lesson->id]);

        $this->actingAs($teacher2);

        $response = $this->get(route('exams.edit', $exam->id));
        $response->assertStatus(403);
    }

    /** @test */
    public function student_cannot_access_exam_if_not_enrolled_or_in_group()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);
        $lesson = Lesson::factory()->create(['teacher_id' => $teacher->id]);
        $exam = Exam::factory()->create(['teacher_id' => $teacher->id, 'lesson_id' => $lesson->id]);

        $this->actingAs($student);

        $response = $this->get(route('student.exams.show', $exam->id));
        $response->assertStatus(403);
    }

    /** @test */
    public function student_can_view_exam_result_with_all_questions_and_correct_answers()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);
        $lesson = Lesson::factory()->create(['teacher_id' => $teacher->id]);

        $exam = Exam::factory()->create([
            'lesson_id' => $lesson->id,
            'teacher_id' => $teacher->id,
            'total_degree' => 20,
        ]);

        // Create 2 questions
        $question1 = ExamQuestion::factory()->create([
            'exam_id' => $exam->id,
            'title' => 'What is PHP?',
            'degree' => 10,
        ]);
        $question2 = ExamQuestion::factory()->create([
            'exam_id' => $exam->id,
            'title' => 'What is Laravel?',
            'degree' => 10,
        ]);

        // Options for question 1
        $opt1 = ExamQuestionOption::factory()->create([
            'exam_question_id' => $question1->id,
            'title' => 'A programming language',
            'is_correct' => 1,
        ]);
        $opt2 = ExamQuestionOption::factory()->create([
            'exam_question_id' => $question1->id,
            'title' => 'A database',
            'is_correct' => 0,
        ]);

        // Options for question 2
        $opt3 = ExamQuestionOption::factory()->create([
            'exam_question_id' => $question2->id,
            'title' => 'A PHP framework',
            'is_correct' => 1,
        ]);
        $opt4 = ExamQuestionOption::factory()->create([
            'exam_question_id' => $question2->id,
            'title' => 'An operating system',
            'is_correct' => 0,
        ]);

        // Student answered only the first question correctly
        $answer = ExamAnswer::factory()->create([
            'student_id' => $student->id,
            'exam_question_id' => $question1->id,
            'exam_question_option_id' => $opt1->id,
        ]);

        // Create result record
        ExamResult::factory()->create([
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'student_degree' => 10,
        ]);

        $this->actingAs($student);

        $response = $this->get(route('student.exams.result', $exam->id));

        $response->assertStatus(200);
        $response->assertViewHas('exam');
        $response->assertViewHas('result');
        $response->assertViewHas('questions');

        $viewData = $response->viewData('questions');

        // Make sure both questions are included
        $this->assertCount(2, $viewData);

        // Check first question has correct option
        $this->assertEquals('What is PHP?', $viewData[0]['question']->title);
        $this->assertEquals($opt1->id, $viewData[0]['correctOption']->id);

        // Second question also has correct option even if unanswered
        $this->assertEquals('What is Laravel?', $viewData[1]['question']->title);
        $this->assertEquals($opt3->id, $viewData[1]['correctOption']->id);
    }

    /** @test */
    public function student_cannot_view_result_if_not_taken_exam()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);
        $lesson = Lesson::factory()->create(['teacher_id' => $teacher->id]);
        $exam = Exam::factory()->create(['teacher_id' => $teacher->id, 'lesson_id' => $lesson->id]);

        $this->actingAs($student);

        $this->withoutExceptionHandling();
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        // Should abort 403
        $this->get(route('student.exams.result', $exam->id));
    }
}
