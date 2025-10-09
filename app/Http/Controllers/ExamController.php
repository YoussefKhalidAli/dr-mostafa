<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ExamQuestion;
use App\Models\ExamQuestionOption;
use App\Models\ExamAnswer;
use App\Models\ExamResult;
use App\Models\ExamAttempt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
class ExamController extends Controller
{
    // 🟢 المدرس: عرض كل الامتحانات اللي عملها
   public function index(Request $request)
{
    $query = Exam::where('teacher_id', Auth::id())
        ->with(['lesson.course', 'group']);

    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    $exams = $query->get();

    $now = now()->addHours(3);

    $upcomingExams = $exams->filter(fn($exam) => $exam->start_time && $exam->start_time > $now);
    $recentExams   = $exams->filter(fn($exam) =>
        ($exam->start_time && $exam->start_time <= $now && $exam->end_time && $exam->end_time >= $now)
        || ($exam->is_open)
    );
    $pastExams     = $exams->filter(fn($exam) => $exam->end_time && $exam->end_time < $now);

    $lessons = Lesson::with('course')
        ->whereHas('course', fn($q) => $q->where('teacher_id', Auth::id()))
        ->get();

    $groups = \App\Models\Group::where('teacher_id', Auth::id())->get();

    return view('exams.index', compact('upcomingExams', 'recentExams', 'pastExams', 'lessons', 'groups'));
}




    // 🟢 المدرس: صفحة إنشاء امتحان جديد
    public function create()
    {
        $lessons = Lesson::with('course')
            ->whereHas('course', fn($q) => $q->where('teacher_id', Auth::id()))
            ->get();

        return view('exams.create', compact('lessons'));
    }

    // 🟢 المدرس: حفظ امتحان جديد
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'lesson_id'   => 'required|exists:lessons,id',
            'group_id'    => 'nullable|exists:groups,id',
            'start_time'  => 'nullable|date',
            'end_time'    => 'nullable|date|after_or_equal:start_time',
            'duration'    => 'nullable|integer',
            'is_open'     => 'boolean',
            'is_limited'  => 'boolean',
            'total_degree'=> 'required|integer|min:1',
        ]);

        $data['teacher_id'] = Auth::id();

        Exam::create($data);

        return redirect()->route('exams.index')->with('success', 'Exam created successfully.');
    }

    // 🟢 المدرس: عرض امتحان معين
    public function show($id)
    {
        $exam = Exam::with(['lesson.course', 'questions.options', 'group'])
            ->findOrFail($id);

        if ($exam->teacher_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('exams.show', compact('exam'));
    }

    // 🟢 المدرس: صفحة تعديل امتحان
    public function edit($id)
{
    $exam = Exam::with(['lesson.course', 'group'])->findOrFail($id);

    if ($exam->teacher_id != Auth::id()) {
        abort(403, 'Unauthorized');
    }

    $lessons = Lesson::with('course')
        ->whereHas('course', function ($q) {
            $q->where('teacher_id', Auth::id());
        })
        ->get();

    $groups = \App\Models\Group::where('teacher_id', Auth::id())->get();

    return view('exams.edit', compact('exam', 'lessons', 'groups'));
}


    // 🟢 المدرس: تعديل امتحان
    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        if ($exam->teacher_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $data = $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'lesson_id'   => 'sometimes|exists:lessons,id',
            'group_id'    => 'nullable|exists:groups,id',
            'start_time'  => 'nullable|date',
            'end_time'    => 'nullable|date|after_or_equal:start_time',
            'duration'    => 'nullable|integer',
            'is_open'     => 'boolean',
            'is_limited'  => 'boolean',
            'total_degree'=> 'integer|min:1',
        ]);

        $exam->update($data);

        return redirect()->route('exams.index')->with('success', 'Exam updated successfully.');
    }

    // 🟢 المدرس: حذف امتحان
    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);

        if ($exam->teacher_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $exam->delete();

        return redirect()->route('exams.index')->with('success', 'Exam deleted successfully.');
    }

    // 🟢 الطالب: عرض الامتحانات المتاحة له
   public function availableExams()
{
    $studentId = Auth::id();

    $exams = Exam::where(function ($q) use ($studentId) {
            $q->whereHas('lesson.course.enrollments', function ($q2) use ($studentId) {
                $q2->where('student_id', $studentId)
                   ->where('status', 'approved');
            });
        })
        ->orWhere(function ($q) use ($studentId) {
            $q->whereHas('group.members', function ($q2) use ($studentId) {
                $q2->where('student_id', $studentId)
                   ->where('status', 'approved');
            });
        })
        ->with([
            'lesson.course',
            'group',
            'results' => function ($q) use ($studentId) {
                $q->where('student_id', $studentId);
            }
        ])
        ->get();

    return view('student.exams.index', compact('exams'));
}


    

public function addQuestion(Request $request, $examId)
{
    $exam = Exam::findOrFail($examId);

    if ($exam->teacher_id != Auth::id()) {
        return back()->with('error', 'غير مصرح لك');
    }

    $data = $request->validate([
        'title'   => 'required|string|max:255',
        'degree'  => 'required|integer|min:1',
        'options' => 'required|array|min:1',
        'options.*.title' => 'required|string|max:255',
        'correct_option' => 'required|integer',
    ]);

    // إنشاء السؤال
    $question = ExamQuestion::create([
        'exam_id' => $exam->id,
        'title'   => $data['title'],
        'degree'  => $data['degree'],
    ]);

    // إدخال الاختيارات
    foreach ($data['options'] as $index => $opt) {
        ExamQuestionOption::create([
            'exam_question_id' => $question->id,
            'title'            => $opt['title'],
            'is_correct'       => ($data['correct_option'] == $index),
        ]);
    }

    return redirect()->route('exams.show', $exam->id)
                     ->with('success', 'تم إضافة السؤال بنجاح');
}


// 🔹 عرض فورم تعديل السؤال
public function quesEdit($id)
{
    $question = ExamQuestion::with('options', 'exam')->findOrFail($id);

    if ($question->exam->teacher_id != Auth::id()) {
        abort(403, 'غير مصرح لك');
    }

    return view('exams.edit-question', compact('question'));
}

// 🔹 تحديث السؤال
public function quesUpdate(Request $request, $id)
{
    $question = ExamQuestion::with('exam')->findOrFail($id);

    if ($question->exam->teacher_id != Auth::id()) {
        abort(403, 'غير مصرح لك');
    }

    $data = $request->validate([
        'title'   => 'required|string|max:255',
        'degree'  => 'required|integer|min:1',
        'options' => 'required|array|min:2',
        'options.*.title' => 'required|string|max:255',
        'correct_option' => 'required|integer',
    ]);

    // تحديث السؤال
    $question->update([
        'title'  => $data['title'],
        'degree' => $data['degree'],
    ]);

    // مسح الخيارات القديمة
    $question->options()->delete();

    // إعادة إدخال الخيارات
    foreach ($data['options'] as $index => $opt) {
        ExamQuestionOption::create([
            'exam_question_id' => $question->id,
            'title'            => $opt['title'],
            'is_correct'       => ($data['correct_option'] == $index),
        ]);
    }

    return redirect()->route('exams.show', $question->exam_id)
                     ->with('success', 'تم تعديل السؤال بنجاح');
}

// 🔹 حذف السؤال
public function quesDestroy($id)
{
    $question = ExamQuestion::with('exam')->findOrFail($id);

    if ($question->exam->teacher_id != Auth::id()) {
        abort(403, 'غير مصرح لك');
    }

    $question->options()->delete();
    $question->delete();

    return redirect()->route('exams.show', $question->exam_id)
                     ->with('success', 'تم حذف السؤال بنجاح');
}
// STUDENT Funcs
// 🟢 الطالب: عرض تفاصيل امتحان
public function showExam($id)
{
    $exam = Exam::with(['lesson.course.enrollments', 'group.members', 'questions.options'])
        ->findOrFail($id);

    $studentId = Auth::id();

    // التحقق أن الطالب مسجل في الكورس أو عضو في المجموعة
    $isEnrolled = $exam->lesson && $exam->lesson->course->enrollments()
        ->where('student_id', $studentId)->where('status', 'approved')->exists();

    $inGroup = $exam->group && $exam->group->members()
        ->where('student_id', $studentId)->where('status', 'approved')->exists();

    if (! $isEnrolled && ! $inGroup) {
        abort(403, 'غير مصرح لك بدخول هذا الامتحان');
    }

    return view('student.exams.show', compact('exam'));
}



public function start($id)
{
    $exam = Exam::findOrFail($id);
    $student = auth()->user();

    // البحث عن محاولة الطالب الحالية أو إنشائها
    $attempt = ExamAttempt::firstOrCreate(
        [
            'exam_id'    => $exam->id,
            'student_id' => $student->id,
        ],
        [
            'started_at' => now(), // إزالة addHours(3) لأنها قد تسبب مشاكل
        ]
    );

    // مدة الامتحان بالدقايق (من قاعدة البيانات)
    $durationMinutes = (int) $exam->duration;
    $durationSeconds = $durationMinutes * 60;

    // حساب الوقت المستهلك (استخدم Carbon بدلاً من now())
    $startedAt = Carbon::parse($attempt->started_at);
    $currentTime = now();
    $elapsed = $currentTime->diffInSeconds($startedAt, false);

    // التأكد من أن الوقت المنقضي ليس سالباً
    $elapsed = max(0, $elapsed);

    // الوقت المتبقي
    $remaining = max($durationSeconds - $elapsed, 0);

    // لو الوقت انتهى → تسليم تلقائي
    if ($remaining <= 0) {
        return $this->submitExam($exam->id, true); // تمرير معامل للتسليم التلقائي
    }

    return view('student.exams.attempt', [
        'exam'     => $exam,
        'attempt'  => $attempt,
        'duration' => $remaining, // بالثواني
    ]);
}

// public function submit(Request $request, $id)
// {
//     $exam = Exam::findOrFail($id);
//     $student = auth()->user();
    
//     $attempt = ExamAttempt::where([
//         'exam_id' => $exam->id,
//         'student_id' => $student->id,
//     ])->first();

//     if (!$attempt) {
//         return redirect()->route('student.exams.index')
//             ->with('error', 'لم يتم العثور على محاولة امتحان صحيحة.');
//     }

//     // فحص إذا كان التسليم تلقائياً
//     $autoSubmit = $request->has('auto_submit') && $request->auto_submit == '1';
    
//     // حفظ الإجابات
//     $this->saveAnswers($request, $attempt);
    
//     // تحديث وقت الانتهاء
//     $attempt->update([
//         'ended_at' => now(),
//         'submitted' => true,
//         'auto_submitted' => $autoSubmit,
//     ]);

//     $message = $autoSubmit 
//         ? '⏰ تم تسليم الامتحان تلقائياً بعد انتهاء الوقت المحدد.'
//         : '✅ تم تسليم الامتحان بنجاح!';

//     return redirect()->route('student.exams.result', $exam->id)
//         ->with('success', $message);
// }

private function saveAnswers(Request $request, $attempt)
{
    // حفظ الإجابات في قاعدة البيانات
    foreach ($request->all() as $key => $value) {
        if (strpos($key, 'question_') === 0) {
            $questionId = str_replace('question_', '', $key);
            
            ExamAnswer::updateOrCreate([
                'exam_attempt_id' => $attempt->id,
                'question_id' => $questionId,
            ], [
                'answer' => $value,
            ]);
        }
    }
}



// 🟢 الطالب: عرض النتيجة

public function result($id)
{
    $exam = Exam::with(['questions.options'])->findOrFail($id);

    $result = $exam->results()
        ->where('student_id', Auth::id())
        ->first();

    if (!$result) {
        abort(403, 'لم تقم بحل هذا الامتحان');
    }

    // نجيب كل الأسئلة مع إجابة الطالب إن وجدت
    $questions = $exam->questions->map(function ($question) use ($result) {
        $answer = $question->answers()
            ->where('student_id', Auth::id())
            ->first();

        $correctOption = $question->options->where('is_correct', 1)->first();

        return [
            'question' => $question,
            'answer' => $answer,
            'chosenOption' => $answer?->chosenOption,
            'correctOption' => $correctOption,
        ];
    });

    return view('student.exams.result', compact('exam', 'result', 'questions'));
}

public function attemptData($id)
{
    $exam = Exam::with(['questions.options'])->findOrFail($id);
    $studentId = Auth::id();

    // نحصل أو ننشئ المحاولة (لن يتم تكرار started_at لو كانت موجودة)
    $attempt = ExamAttempt::firstOrCreate(
        ['exam_id' => $exam->id, 'student_id' => $studentId],
        ['started_at' => now()]
    );

    // الإجابات المحفوظة المرتبطة بهذه المحاولة (map by question id)
    $saved = ExamAnswer::where('exam_attempt_id', $attempt->id)
        ->get()
        ->keyBy('exam_question_id')
        ->map(function($a) {
            return [
                'option_id' => $a->exam_question_option_id,
                'degree' => $a->degree ?? null,
            ];
        });

    return response()->json([
        'success' => true,
        'attempt' => [
            'id' => $attempt->id,
            'started_at' => $attempt->started_at ? $attempt->started_at->toISOString() : now()->toISOString(),
        ],
        'exam' => [
            'id' => $exam->id,
            'duration_minutes' => (int) $exam->duration,
        ],
        'saved_answers' => $saved,
    ]);
}

// حفظ إجابة واحدة عند اختيار المستخدم (AJAX)
public function saveAnswerAjax(Request $request, $id)
{
    $request->validate([
        'question_id' => 'required|integer',
        'option_id'   => 'nullable|integer',
    ]);

    $exam = Exam::findOrFail($id);
    $studentId = Auth::id();

    $attempt = ExamAttempt::where('exam_id', $exam->id)
        ->where('student_id', $studentId)
        ->firstOrFail();

    $questionId = $request->question_id;
    $optionId = $request->option_id;

    // حفظ/تحديث إجابة مرتبطة بالمحاولة
    $answer = ExamAnswer::updateOrCreate(
        [
            'exam_attempt_id' => $attempt->id,
            'exam_question_id' => $questionId,
        ],
        [
            'exam_question_option_id' => $optionId,
            'student_id' => $studentId,
            // لو عندك عمود answer نصي يمكنك إضافته هنا: 'answer' => $optionId
        ]
    );

    return response()->json(['success' => true, 'answer_id' => $answer->id]);
}

// تسليم تلقائي عبر AJAX (عند انتهاء الوقت)
public function autoSubmitAjax(Request $request, $id)
{
    $exam = Exam::with(['questions.options'])->findOrFail($id);
    $studentId = Auth::id();

    $attempt = ExamAttempt::where('exam_id', $exam->id)
        ->where('student_id', $studentId)
        ->firstOrFail();

    // منع التسليم المزدوج
    if ($attempt->submitted) {
        return response()->json(['success' => true, 'redirect' => route('student.exams.result', $exam->id)]);
    }

    // حساب النتيجة اعتمادًا على الإجابات الموجودة في ExamAnswer المرتبطة بهذه المحاولة
    $answers = ExamAnswer::where('exam_attempt_id', $attempt->id)
        ->get()
        ->keyBy('exam_question_id');

    $totalScore = 0;
    foreach ($exam->questions as $question) {
        $saved = $answers->get($question->id);
        $correctOption = $question->options->firstWhere('is_correct', 1);
        if ($saved && $saved->exam_question_option_id && $correctOption && $saved->exam_question_option_id == $correctOption->id) {
            $totalScore += $question->degree;
            // نحدّث درجة الإجابة إن رغبت
            $saved->update(['degree' => $question->degree]);
        } else {
            if ($saved) {
                $saved->update(['degree' => 0]);
            }
        }
    }

    // سجل النتيجة إن لم تكن موجودة
    $result = ExamResult::firstOrCreate(
        ['exam_id' => $exam->id, 'student_id' => $studentId],
        ['student_degree' => $totalScore]
    );

    // حدث المحاولة
    $attempt->update([
        'ended_at' => now(),
        'submitted' => true,
        'auto_submitted' => true,
        'submitted_at' => now(),
    ]);

    return response()->json(['success' => true, 'redirect' => route('student.exams.result', $exam->id)]);
}

// عدّل دالة submit الحالية لتدعم الحالات التي تم حفظ الإجابات فيها مسبقاً
public function submit(Request $request, $id)
{
    $exam = Exam::findOrFail($id);
    $student = auth()->user();

    $attempt = ExamAttempt::where([
        'exam_id' => $exam->id,
        'student_id' => $student->id,
    ])->first();

    if (!$attempt) {
        return redirect()->route('student.exams.index')
            ->with('error', 'لم يتم العثور على محاولة امتحان صحيحة.');
    }

    // حاول حفظ إجابات من الفورم (لو وُجِدَت)
    $this->saveAnswers($request, $attempt);

    // الآن حساب النتيجة باستخدام الإجابات المحفوظة في DB (كما في autoSubmitAjax)
    $answers = ExamAnswer::where('exam_attempt_id', $attempt->id)
        ->get()
        ->keyBy('exam_question_id');

    $examWithQuestions = Exam::with('questions.options')->find($exam->id);
    $totalScore = 0;
    foreach ($examWithQuestions->questions as $question) {
        $saved = $answers->get($question->id);
        $correctOption = $question->options->firstWhere('is_correct', 1);
        if ($saved && $saved->exam_question_option_id && $correctOption && $saved->exam_question_option_id == $correctOption->id) {
            $totalScore += $question->degree;
            $saved->update(['degree' => $question->degree]);
        } else {
            if ($saved) {
                $saved->update(['degree' => 0]);
            }
        }
    }

    // سجل أو حدّث النتيجة
    ExamResult::updateOrCreate(
        ['exam_id' => $exam->id, 'student_id' => $student->id],
        ['student_degree' => $totalScore]
    );

    $attempt->update([
        'ended_at' => now(),
        'submitted' => true,
        'auto_submitted' => $request->has('auto_submit') && $request->auto_submit == '1',
        'submitted_at' => now(),
    ]);

    $message = ($request->has('auto_submit') && $request->auto_submit == '1')
        ? '⏰ تم تسليم الامتحان تلقائياً بعد انتهاء الوقت المحدد.'
        : '✅ تم تسليم الامتحان بنجاح!';

    return redirect()->route('student.exams.result', $exam->id)
        ->with('success', $message);
}
}
