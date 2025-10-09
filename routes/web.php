<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\AssignmentAnswerController;
// use App\Http\Middleware\Role as role;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard Routes
Route::get('teacher/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'role:teacher'])->name('dashboard');
// Route::get('/', view('welcome'));

// Authentication Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Courses Routes
Route::prefix('courses')->name('courses.')->group(function () {
    Route::get('/', [CourseController::class, 'index'])->name('index')->middleware(['auth', 'role:teacher']);
    Route::get('/create', [CourseController::class, 'create'])->name('create')->middleware(['auth', 'role:teacher']);
    Route::post('/', [CourseController::class, 'store'])->name('store')->middleware(['auth', 'role:teacher'])->middleware(['auth', 'role:teacher']);
    Route::get('/{course}', [CourseController::class, 'show'])->name('show')->middleware(['auth', 'role:teacher'])->middleware(['auth', 'role:teacher']);
    Route::get('/{course}/edit', [CourseController::class, 'edit'])->name('edit')->middleware(['auth', 'role:teacher'])->middleware(['auth', 'role:teacher']);
    Route::put('/{course}', [CourseController::class, 'update'])->name('update')->middleware(['auth', 'role:teacher'])->middleware(['auth', 'role:teacher']);
    Route::delete('/{course}', [CourseController::class, 'destroy'])->name('destroy')->middleware(['auth', 'role:teacher'])->middleware(['auth', 'role:teacher']);
});

// Lessons Routes
Route::prefix('lessons')->name('lessons.')->group(function () {
    Route::get('/', [LessonController::class, 'index'])->name('index')->middleware(['auth', 'role:teacher']);
    Route::get('/create', [LessonController::class, 'create'])->name('create')->middleware(['auth', 'role:teacher']);
    Route::post('/', [LessonController::class, 'store'])->name('store')->middleware(['auth', 'role:teacher']);
    Route::get('/{lesson}', [LessonController::class, 'show'])->name('show')->middleware(['auth', 'role:teacher']);
    Route::get('/{lesson}/edit', [LessonController::class, 'edit'])->name('edit')->middleware(['auth', 'role:teacher']);
    Route::put('/{lesson}', [LessonController::class, 'update'])->name('update')->middleware(['auth', 'role:teacher']);
    Route::delete('/{lesson}', [LessonController::class, 'destroy'])->name('destroy')->middleware(['auth', 'role:teacher']);
});






// Teacher Group Management Routes
Route::middleware(['auth'])->prefix('teacher')->name('teacher.')->group(function () {

    // Group CRUD operations
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index')->middleware(['auth', 'role:teacher']);
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store')->middleware(['auth', 'role:teacher']);
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create')->middleware(['auth', 'role:teacher']);
    Route::get('/groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit')->middleware(['auth', 'role:teacher']);
    Route::put('/groups/{group}', [GroupController::class, 'update'])->name('groups.update')->middleware(['auth', 'role:teacher']);
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show')->middleware(['auth', 'role:teacher']);
    Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy')->middleware(['auth', 'role:teacher']);

    // Group member management
    Route::post('/groups/add-student', [GroupController::class, 'addStudent'])->name('groups.add-student')->middleware(['auth', 'role:teacher']);
    Route::patch('/groups/requests/{groupMember}/approve', [GroupController::class, 'approveRequest'])->name('groups.approve-request')->middleware(['auth', 'role:teacher']);
    Route::patch('/groups/requests/{groupMember}/reject', [GroupController::class, 'rejectRequest'])->name('groups.reject-request')->middleware(['auth', 'role:teacher']);
    Route::delete('/groups/{group}/members/{member}', [GroupController::class, 'removeMember'])->name('groups.remove-member')->middleware(['auth', 'role:teacher']);

    // Group statistics
    Route::get('/groups/{group}/stats', [GroupController::class, 'getGroupStats'])->name('groups.stats')->middleware(['auth', 'role:teacher']);

    // AJAX routes
    Route::get('/groups/search/students', [GroupController::class, 'searchStudents'])->name('groups.search-students')->middleware(['auth', 'role:teacher']);
});


// Student Group Routes (for joining groups)
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::post('/groups/{group}/join', [GroupController::class, 'joinGroup'])->name('groups.join');
    
});

// Sessions Routes
Route::prefix('sessions')->middleware(['auth', 'role:teacher'])->name('sessions.')->group(function () {
    Route::get('/', [SessionController::class, 'index'])->name('index');
    Route::get('/create', [SessionController::class, 'create'])->name('create');
    Route::post('/', [SessionController::class, 'store'])->name('store');
    Route::get('/{session}', [SessionController::class, 'show'])->name('show');
    Route::get('/{session}/edit', [SessionController::class, 'edit'])->name('edit');
    Route::put('/{session}', [SessionController::class, 'update'])->name('update');
    Route::delete('/{session}', [SessionController::class, 'destroy'])->name('destroy');
});


Route::middleware(['auth', 'role:teacher'])->group(function () {
    // الواجبات
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/{id}', [AssignmentController::class, 'show'])->name('assignments.show');
    Route::get('/assignments/{id}/edit', [AssignmentController::class, 'edit'])->name('assignments.edit');
    Route::put('/assignments/{id}', [AssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('/assignments/{id}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
    Route::delete('/assignments/{id}/files/{index}', [AssignmentController::class, 'deleteFile'])
    ->name('assignments.deleteFile');
    Route::get('/answers/{id}', [AssignmentAnswerController::class, 'show'])->name('answers.show');
    Route::put('/answers/{id}', [AssignmentAnswerController::class, 'update'])->name('answers.update');
});
// 🟢 واجبات الطالب
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // قائمة الواجبات
    Route::get('/assignments', [AssignmentController::class, 'studentIndex'])->name('assignments.index');

    // عرض واجب معين
    Route::get('/assignments/{id}', [AssignmentController::class, 'studentShow'])->name('assignments.show');

    // تسليم الواجب
    Route::post('/assignments/{id}/submit', [AssignmentAnswerController::class, 'submit'])->name('assignments.submit');
    Route::post('/assignments/{id}/resubmit', [AssignmentAnswerController::class, 'resubmit'])
    ->name('assignments.resubmit');

    // عرض النتيجة
    Route::get('/assignments/{id}/result', [AssignmentAnswerController::class, 'result'])->name('assignments.result');
});

// Exams Routes
Route::middleware(['auth', 'role:teacher'])->group(function () {
    // الامتحانات
    Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');     // عرض كل الامتحانات
    Route::get('/exams/create', [ExamController::class, 'index'])->name('exams.create'); // فورم إنشاء امتحان
    Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');   // حفظ امتحان جديد

    Route::get('/exams/{id}', [ExamController::class, 'show'])->name('exams.show'); // عرض امتحان
    Route::get('/exams/{id}/edit', [ExamController::class, 'edit'])->name('exams.edit'); // تعديل امتحان
    Route::put('/exams/{id}', [ExamController::class, 'update'])->name('exams.update');  // تحديث امتحان
    Route::delete('/exams/{id}', [ExamController::class, 'destroy'])->name('exams.destroy'); // حذف امتحان

    // الأسئلة
    Route::post('/exams/{exam}/add-question', [ExamController::class, 'addQuestion'])->name('exams.addQuestion');
    Route::get('/questions/{id}/edit', [ExamController::class, 'quesEdit'])->name('questions.edit');
    Route::put('/questions/{id}', [ExamController::class, 'quesUpdate'])->name('questions.update');
    Route::delete('/questions/{id}', [ExamController::class, 'quesDestroy'])->name('questions.destroy');

    // الطالب
    // Route::get('/student/exams', [ExamController::class, 'availableExams'])->name('student.exams');
});

// ==========================
// 🟢 Student Exams Routes
// ==========================
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.exams.')->group(function () {

    // عرض قائمة الامتحانات المتاحة للطالب
    Route::get('/exams', [ExamController::class, 'availableExams'])->name('index');

    // تفاصيل الامتحان قبل البدء
    Route::get('/exams/{id}', [ExamController::class, 'showExam'])->name('show');

    // شاشة التعليمات قبل البدء
    Route::get('/exams/{id}/start', [ExamController::class, 'start'])->name('start');

    // محاولة الامتحان (عرض الأسئلة + المؤقت)
    Route::get('/exams/{id}/attempt', [ExamController::class, 'start'])->name('attempt');

    // تسليم الامتحان
    Route::post('/exams/{id}/submit', [ExamController::class, 'submit'])->name('submit');

    // عرض النتيجة
    Route::get('/exams/{id}/result', [ExamController::class, 'result'])->name('result');
    // student exam AJAX routes
Route::get('/exams/{id}/attempt-data', [ExamController::class, 'attemptData'])
    ->name('attempt_data');

Route::post('/exams/{id}/save-answer', [ExamController::class, 'saveAnswerAjax'])
    ->name('save_answer');

Route::post('/exams/{id}/auto-submit', [ExamController::class, 'autoSubmitAjax'])
    ->name('auto_submit');

});


// Students Routes
Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    Route::get('/', [StudentController::class, 'home'])->name('index');
    Route::get('/{student}', [StudentController::class, 'show'])->name('show');
    Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit');
    Route::put('/{student}', [StudentController::class, 'update'])->name('update');
});

// Enrollments Routes
Route::prefix('enrollments')->name('enrollments.')->group(function () {
    Route::get('/', [EnrollmentController::class, 'index'])->name('index')->middleware(['auth', 'role:teacher']);
    Route::post('/{course}', [EnrollmentController::class, 'store'])->name('store')->middleware(['auth', 'role:student']);;
    Route::put('/{enrollment}/approve', [EnrollmentController::class, 'approve'])->name('approve')->middleware(['auth', 'role:teacher']);
    Route::put('/{enrollment}/reject', [EnrollmentController::class, 'reject'])->name('reject')->middleware(['auth', 'role:teacher']);
    Route::put('/{enrollment}/complete', [EnrollmentController::class, 'complete'])->name('complete')->middleware(['auth', 'role:teacher']);
    Route::delete('/{enrollment}', [EnrollmentController::class, 'destroy'])->name('destroy')->middleware(['auth', 'role:teacher']);
});

// DASHBOARD
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/home', [DashboardController::class, 'home'])->name('home');
    // Route::get('/groups', [DashboardController::class, 'groups'])->name('groups');
    Route::get('/sessions', [DashboardController::class, 'sessions'])->name('sessions');
});

Route::prefix('student')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/home', [StudentController::class, 'home'])->name('student.home');
    Route::get('/groups', [StudentController::class, 'groups'])->name('student.groups');
    Route::post('/groups/{id}/join', [StudentController::class, 'requestJoin'])->name('student.groups.join');
    Route::get('/sessions', [StudentController::class, 'sessions'])->name('student.sessions');
});
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/courses', [StudentController::class, 'courses'])->name('courses');
    Route::get('/courses/{course}', [StudentController::class, 'showCourse'])->name('courses.show');
     Route::get('/courses/{course}/lessons/{lesson}', [StudentController::class, 'showLesson'])->name('lessons.show');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/lessons/{lesson}/video', [LessonController::class, 'streamVideo'])
        ->name('lessons.video');
});

use App\Http\Controllers\ContactController;
Route::post('/teacher/contact', [ContactController::class, 'store'])->name('contact.store');
Route::prefix('/teacher/contact')->name('teacher.contact.')->middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/', [ContactController::class, 'index'])->name('index');
    Route::get('/{id}', [ContactController::class, 'show'])->name('show');
    Route::delete('/{id}', [ContactController::class, 'destroy'])->name('destroy');
});

require __DIR__.'/auth.php';