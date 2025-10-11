<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display all chats (for teacher) or single chat (for student).
     */
   public function index()
{
    $user = Auth::user();

    // 🧑‍🏫 Teacher side
    if ($user->isTeacher()) {
        // Get all students who have chatted with the teacher
        $students = User::where('role', 'student')
            ->where(function ($q) use ($user) {
                $q->whereHas('chatsAsSender', function ($query) use ($user) {
                    $query->where('receiver_id', $user->id);
                })->orWhereHas('chatsAsReceiver', function ($query) use ($user) {
                    $query->where('sender_id', $user->id);
                });
            })
            ->get()
            ->map(function ($student) use ($user) {
                // 🔹 Get latest message (from student or teacher)
                $lastMessage = Chat::where(function ($q) use ($student, $user) {
        $q->where('sender_id', $student->id)
          ->where('receiver_id', $user->id);
    })
    ->orWhere(function ($q) use ($student, $user) {
        $q->where('sender_id', $user->id)
          ->where('receiver_id', $student->id);
    })
    ->orderByDesc('created_at')
    ->orderByDesc('id') // <- إضافة ضامنة للترتيب
    ->first();


                // 🔹 Assign dynamic properties for view
                $student->last_message = $lastMessage?->message ?? 'لا توجد رسائل بعد';
                $student->last_message_time = $lastMessage?->created_at?->diffForHumans();

                // 🔹 Check if unread messages exist
                $student->unread = Chat::where('sender_id', $student->id)
                    ->where('receiver_id', $user->id)
                    ->whereNull('read_at')
                    ->exists();

                return $student;
            });

        // 🔄 If it's an AJAX request (for real-time refresh)
        if (request()->ajax()) {
            return view('teacher.chat.partials.student-list', compact('students'));
        }

        return view('teacher.chat.index', compact('students'));
    }

    // 🧑‍🎓 Student side
    $teacher = User::where('role', 'teacher')->firstOrFail();

    $messages = Chat::where(function ($q) use ($user, $teacher) {
            $q->where('sender_id', $user->id)
              ->where('receiver_id', $teacher->id);
        })
        ->orWhere(function ($q) use ($user, $teacher) {
            $q->where('sender_id', $teacher->id)
              ->where('receiver_id', $user->id);
        })
        ->orderBy('created_at', 'asc')
        ->get();

    // Return partial view for AJAX refresh
    if (request()->ajax()) {
        return view('student.chat.partials.messages', compact('messages'));
    }

    return view('student.chat.index', compact('messages', 'teacher'));
}


public function show($studentId)
{
    $teacher = Auth::user();
    $student = User::findOrFail($studentId);

    // Get chat messages
    $messages = Chat::where(function ($q) use ($student, $teacher) {
        $q->where('sender_id', $student->id)
          ->where('receiver_id', $teacher->id);
    })->orWhere(function ($q) use ($student, $teacher) {
        $q->where('sender_id', $teacher->id)
          ->where('receiver_id', $student->id);
    })->orderBy('created_at')->get();

    // Mark as read
    Chat::where('sender_id', $student->id)
        ->where('receiver_id', $teacher->id)
        ->whereNull('read_at')
        ->update(['read_at' => now()]);

    if (request()->ajax()) {
        return view('teacher.chat.partials.messages', compact('messages'));
    }

    return view('teacher.chat.show', compact('student', 'messages'));
}


    /**
     * Store a new message.
     */
    public function store(Request $request, $receiverId = null)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $user = Auth::user();

        if ($user->isTeacher()) {
            // Teacher sends to a specific student
            $receiver = User::findOrFail($request->input('receiver_id'));
        } else {
            // Student sends to their teacher
            $receiver = User::where('role', 'teacher')->firstOrFail();
        }

        Chat::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'تم إرسال الرسالة بنجاح!');
    }
}
