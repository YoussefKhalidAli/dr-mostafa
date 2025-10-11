<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Chat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $teacher;
    protected $student;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a teacher and student
        $this->teacher = User::factory()->create(['role' => 'teacher']);
        $this->student = User::factory()->create(['role' => 'student']);
    }

    /** @test */
    public function student_can_send_message_to_teacher()
    {
        $this->actingAs($this->student);

        $response = $this->post(route('student.chat.store'), [
            'message' => 'Hello Teacher!',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('chats', [
            'sender_id' => $this->student->id,
            'receiver_id' => $this->teacher->id,
            'message' => 'Hello Teacher!',
        ]);
    }

    /** @test */
    public function teacher_can_send_message_to_student()
    {
        $this->actingAs($this->teacher);

        $response = $this->post(route('teacher.chat.store', $this->student->id), [
            'message' => 'Hi Student!',
            'receiver_id' => $this->student->id,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('chats', [
            'sender_id' => $this->teacher->id,
            'receiver_id' => $this->student->id,
            'message' => 'Hi Student!',
        ]);
    }

    /** @test */
    public function teacher_can_view_student_chat_and_marks_unread_as_read()
    {
        // Create a message from student to teacher (unread)
        $chat = Chat::create([
            'sender_id' => $this->student->id,
            'receiver_id' => $this->teacher->id,
            'message' => 'Please check my homework!',
        ]);

        $this->actingAs($this->teacher);

        $response = $this->get(route('teacher.chat.show', $this->student->id));

        $response->assertStatus(200);
        $response->assertSee('Please check my homework!');

        $this->assertNotNull($chat->fresh()->read_at);
    }

    /** @test */
    public function teacher_chat_list_shows_students_and_latest_message()
    {
        // Add some chats
        Chat::create([
            'sender_id' => $this->student->id,
            'receiver_id' => $this->teacher->id,
            'message' => 'First Message',
        ]);

        Chat::create([
            'sender_id' => $this->teacher->id,
            'receiver_id' => $this->student->id,
            'message' => 'Second Message',
        ]);

        $this->actingAs($this->teacher);

        $response = $this->get(route('teacher.chat.index'));

        $response->assertStatus(200);
        $response->assertSee($this->student->name);
        $response->assertSee('Second Message');
    }
}
