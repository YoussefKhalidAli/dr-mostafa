<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupSession;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class StudentController extends Controller
{
    /**
     * الصفحة الرئيسية للطالب
     */
    public function home()
    {
        $studentId = Auth::id();

        // حساب المجموعات المعتمدة فقط (غير المحذوفة)
        $approvedGroupsCount = GroupMember::where('student_id', $studentId)
            ->where('status', 'approved')
            ->whereHas('group', function ($q) {
                $q->whereNull('deleted_at'); // التأكد من أن المجموعة غير محذوفة
            })
            ->count();

        // حساب الجلسات لهذا الأسبوع (للمجموعات غير المحذوفة)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $weeklySessionsCount = GroupSession::whereHas('group', function ($q) use ($studentId) {
                $q->whereNull('deleted_at') // التأكد من أن المجموعة غير محذوفة
                 ->whereHas('members', function ($subQ) use ($studentId) {
                    $subQ->where('student_id', $studentId)->where('status', 'approved');
                });
            })
            ->whereBetween('time', [$startOfWeek, $endOfWeek])
            ->count();

        // الجلسات القادمة (أقرب 4 جلسات للمجموعات غير المحذوفة)
        $upcomingSessions = GroupSession::whereHas('group', function ($q) use ($studentId) {
                $q->whereNull('deleted_at') // التأكد من أن المجموعة غير محذوفة
                 ->whereHas('members', function ($subQ) use ($studentId) {
                    $subQ->where('student_id', $studentId)->where('status', 'approved');
                });
            })
            ->with(['group.teacher'])
            ->where('time', '>', Carbon::now())
            ->orderBy('time', 'asc')
            ->limit(4)
            ->get();

        // الجلسة المباشرة الآن (للمجموعات غير المحذوفة)
        $liveSession = GroupSession::whereHas('group', function ($q) use ($studentId) {
                $q->whereNull('deleted_at') // التأكد من أن المجموعة غير محذوفة
                 ->whereHas('members', function ($subQ) use ($studentId) {
                    $subQ->where('student_id', $studentId)->where('status', 'approved');
                });
            })
            ->with(['group.teacher'])
            ->where('time', '<=', Carbon::now())
            ->where('time', '>=', Carbon::now()->subHour()) // افترض أن الجلسة تستمر ساعة
            ->first();

        // المجموعات المنضم إليها (أحدث 3 - غير محذوفة)
        $joinedGroups = Group::withCount('members')
            ->with('teacher')
            ->whereNull('deleted_at') // التأكد من أن المجموعة غير محذوفة
            ->whereHas('members', function ($q) use ($studentId) {
                $q->where('student_id', $studentId)->where('status', 'approved');
            })
            ->latest()
            ->limit(3)
            ->get();

        // المجموعات المتاحة للانضمام (أحدث 4 - غير محذوفة)
        $availableGroups = Group::withCount('members')
            ->with('teacher')
            ->whereNull('deleted_at') // التأكد من أن المجموعة غير محذوفة
            ->whereDoesntHave('members', function ($q) use ($studentId) {
                $q->where('student_id', $studentId);
            })
            ->latest()
            ->limit(4)
            ->get();

        return view('student.home', compact(
            'approvedGroupsCount',
            'weeklySessionsCount',
            'upcomingSessions',
            'liveSession',
            'joinedGroups',
            'availableGroups'
        ));
    }


    /**
     * عرض جميع المجموعات (المنضم لها وغير المنضم لها)
     */
 public function groups()
{
    $studentId = Auth::id();

    // المجموعات اللي الطالب منضم ليها
    $joinedGroups = Group::withCount([
    'members as approved_members_count' => function ($query) {
        $query->where('status', 'approved');
    },
    'sessions',
    'assignments'
])
->whereHas('members', function ($q) use ($studentId) {
    $q->where('student_id', $studentId)
      ->where('status', 'approved');
})
->with(['teacher', 'members' => function ($query) use ($studentId) {
    $query->where('student_id', $studentId);
}])
->get();


    // المجموعات اللي لسه الطالب مقدم عليها (في انتظار موافقة)
    $pendingGroups = Group::withCount(['members'])
        ->whereHas('members', function ($q) use ($studentId) {
            $q->where('student_id', $studentId)->where('status', 'pending');
        })->get();

    // المجموعات اللي الطالب مش منضم ليها ومقدمش عليها
    $availableGroups = Group::withCount(['members'])
        ->whereDoesntHave('members', function ($q) use ($studentId) {
            $q->where('student_id', $studentId);
        })->get();

    return view('student.groups', compact('joinedGroups', 'pendingGroups', 'availableGroups'));
}

    /**
     * عمل طلب انضمام لمجموعة
     */
    public function requestJoin($groupId)
    {
        $studentId = Auth::id();

        $exists = GroupMember::where('group_id', $groupId)
            ->where('student_id', $studentId)
            ->first();

        if ($exists) {
            return back()->with('error', 'لقد قمت بالفعل بإرسال طلب أو أنت عضو في هذه المجموعة.');
        }

        GroupMember::create([
            'group_id' => $groupId,
            'student_id' => $studentId,
            'status' => 'pending',
        ]);

        return back()->with('success', 'تم إرسال طلب الانضمام بنجاح!');
    }

    /**
     * عرض الجلسات الخاصة بالمجموعات اللي الطالب منضم ليها
     */

public function sessions()
{
    $studentId = Auth::id();

    // نجيب المجموعات اللي الطالب عضو فيها
    $groups = Group::whereHas('members', function ($q) use ($studentId) {
        $q->where('student_id', $studentId)->where('status', 'approved');
    })->pluck('id');

    // نجيب الجلسات اللي تخص المجموعات دي بدون تحويل المنطقة الزمنية
    $sessions = GroupSession::whereIn('group_id', $groups)
        ->with(['group.teacher']) // تحميل العلاقات مسبقاً لتحسين الأداء
        ->orderBy('time', 'asc')
        ->get();

    return view('student.sessions', compact('sessions'));
}
public function courses(Request $request)
{
    $student = Auth::user();

    // كورسات مسجل فيها الطالب (مقبولة فقط)
    $enrolledCourses = $student->enrollments()
        ->where('status', 'approved') // أو whatever الحالة اللي بتستخدمها للقبول
        ->with(['course.teacher'])
        ->with(['course' => function($q) {
            $q->withCount('lessons');
        }])
        ->whereHas('course', function($q) use ($request) {
            if ($search = $request->search_enrolled) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            }
        })
        ->paginate(6, ['*'], 'enrolled_page');

    // كورسات في انتظار الموافقة
    $pendingCourses = $student->enrollments()
        ->where('status', 'pending')
        ->with(['course.teacher'])
        ->with(['course' => function($q) {
            $q->withCount('lessons');
        }])
        ->paginate(6, ['*'], 'pending_page');

    // الكورسات المتاحة (غير مسجل فيها خالص)
    $availableCourses = \App\Models\Course::with(['teacher'])
        ->withCount('lessons')
        ->whereDoesntHave('enrollments', function($q) use ($student) {
            $q->where('student_id', $student->id);
        })
        ->when($request->search_available, function($q, $search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        })
        ->paginate(6, ['*'], 'available_page');

    return view('student.courses.index', compact('enrolledCourses', 'pendingCourses', 'availableCourses'));
}

public function showCourse($id)
{
    $student = Auth::user();

    $course = \App\Models\Course::with(['lessons', 'teacher'])
        ->whereHas('enrollments', fn($q) => $q->where('student_id', $student->id))
        ->findOrFail($id);

    return view('student.courses.show', compact('course'));
}
public function showLesson($courseId, $lessonId)
{
    $student = Auth::user();

    // نجيب الكورس اللي الطالب مسجل فيه
    $course = \App\Models\Course::whereHas('enrollments', fn($q) => 
        $q->where('student_id', $student->id)
    )->findOrFail($courseId);

    // نجيب الدرس اللي تبع الكورس
    $lesson = $course->lessons()->findOrFail($lessonId);

    return view('student.lessons.show', compact('course', 'lesson'));
}


}
