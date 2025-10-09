<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    // List all messages
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(10);
        return view('teacher.contact.index', compact('messages'));
    }

    // Show single message
    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        return view('teacher.contact.show', compact('message'));
    }

    // Store new message from guest
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        ContactMessage::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال رسالتك بنجاح، سنتواصل معك قريباً.',
        ]);
    }

    // 🗑️ Delete a message
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        // If request expects JSON (like from AJAX)
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الرسالة بنجاح.',
            ]);
        }

        // Otherwise redirect back (for web UI)
        return redirect()
            ->route('teacher.contact.index')
            ->with('success', 'تم حذف الرسالة بنجاح.');
    }
}
