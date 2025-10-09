<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $teacher;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a fake teacher user
        $this->teacher = User::factory()->create([
            'role' => 'teacher', // assuming you have a 'role' column
        ]);
    }

    /** @test */
    public function it_displays_the_messages_index_page_for_authenticated_teacher()
    {
        $messages = ContactMessage::factory()->count(3)->create();

        $response = $this->actingAs($this->teacher)
                         ->get(route('teacher.contact.index'));

        $response->assertStatus(200);
        $response->assertSee($messages[0]->title);
    }

    /** @test */
    public function it_displays_a_single_message_for_authenticated_teacher()
    {
        $message = ContactMessage::factory()->create();

        $response = $this->actingAs($this->teacher)
                         ->get(route('teacher.contact.show', $message->id));

        $response->assertStatus(200);
        $response->assertSee($message->title);
        $response->assertSee($message->content);
    }

    /** @test */
    public function it_can_store_a_new_message_via_json_publicly()
    {
        $payload = [
            'name' => 'John Doe',
            'phone' => '123456789',
            'title' => 'Hello',
            'content' => 'This is a test message.',
        ];

        $response = $this->postJson(route('contact.store'), $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'تم إرسال رسالتك بنجاح، سنتواصل معك قريباً.',
                 ]);

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'John Doe',
            'title' => 'Hello',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_storing()
    {
        $response = $this->postJson(route('contact.store'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'title', 'content']);
    }

    /** @test */
    public function it_can_delete_a_message_and_redirect_for_authenticated_teacher()
    {
        $message = ContactMessage::factory()->create();

        $response = $this->actingAs($this->teacher)
                         ->delete(route('teacher.contact.destroy', $message->id));

        $response->assertRedirect(route('teacher.contact.index'));
        $this->assertDatabaseMissing('contact_messages', ['id' => $message->id]);
    }

    /** @test */
    public function it_can_delete_a_message_via_json_for_authenticated_teacher()
    {
        $message = ContactMessage::factory()->create();

        $response = $this->actingAs($this->teacher)
                         ->deleteJson(route('teacher.contact.destroy', $message->id));

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'تم حذف الرسالة بنجاح.',
                 ]);

        $this->assertDatabaseMissing('contact_messages', ['id' => $message->id]);
    }
}
