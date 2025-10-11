<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is a teacher.
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /**
     * Check if user is a student.
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Get the courses taught by the teacher.
     */
    public function taughtCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    /**
     * Get the groups managed by the teacher.
     */
    public function managedGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'teacher_id');
    }

    /**
     * Get the enrollments for the user.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class, 'student_id');
    }

    /**
     * Get the courses that the user is enrolled in.
     */
    public function enrolledCourses(): HasManyThrough
    {
        return $this->hasManyThrough(
            Course::class,
            CourseEnrollment::class,
            'student_id',
            'id',
            'id',
            'course_id'
        )->where('course_enrollments.status', 'approved');
    }

    /**
     * Get the group memberships for the user.
     */
    public function groupMemberships(): HasMany
    {
        return $this->hasMany(GroupMember::class, 'student_id');
    }

    /**
     * Get the groups that the user is a member of.
     */
    public function joinedGroups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_members', 'student_id', 'group_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Get the approved groups that the user is a member of.
     */
    public function approvedGroups(): BelongsToMany
    {
        return $this->joinedGroups()->wherePivot('status', 'approved');
    }

    /**
     * Get the assignment answers submitted by the user.
     */
    public function assignmentAnswers(): HasMany
    {
        return $this->hasMany(AssignmentAnswer::class, 'student_id');
    }

    /**
     * Get the exam results for the user.
     */
    public function examResults(): HasMany
    {
        return $this->hasMany(ExamResult::class, 'student_id');
    }

    /**
     * Get the exam answers submitted by the user.
     */
    public function examAnswers(): HasMany
    {
        return $this->hasMany(ExamAnswer::class, 'student_id');
    }

    /**
     * Get the sessions created by the teacher.
     */
    public function createdSessions(): HasMany
    {
        return $this->hasMany(GroupSession::class, 'teacher_id');
    }

    /**
     * Get the assignments created by the teacher.
     */
    public function createdAssignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'teacher_id');
    }

    /**
     * Get the exams created by the teacher.
     */
    public function createdExams(): HasManyThrough
    {
        return $this->hasManyThrough(
            Exam::class,
            Lesson::class,
            'teacher_id',
            'lesson_id',
            'id',
            'id'
        );
    }

    /**
     * Check if user is enrolled in a course.
     */
    public function isEnrolledIn($courseId): bool
    {
        return $this->enrollments()
            ->where('course_id', $courseId)
            ->where('status', 'approved')
            ->exists();
    }

    /**
     * Check if user has completed a course.
     */
    public function hasCompleted($courseId): bool
    {
        return $this->enrollments()
            ->where('course_id', $courseId)
            ->where('status', 'completed')
            ->exists();
    }

    /**
     * Check if user is a member of a group.
     */
    public function isMemberOf($groupId): bool
    {
        return $this->groupMemberships()
            ->where('group_id', $groupId)
            ->where('status', 'approved')
            ->exists();
    }

    /**
     * Check if user is a pending member of a group.
     */
    public function isPendingMemberOf($groupId): bool
    {
        return $this->groupMemberships()
            ->where('group_id', $groupId)
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Scope a query to only include teachers.
     */
    public function scopeTeachers($query)
    {
        return $query->where('role', 'teacher');
    }

    /**
     * Scope a query to only include students.
     */
    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }
    public function chatsAsSender()
{
    return $this->hasMany(Chat::class, 'sender_id');
}

public function chatsAsReceiver()
{
    return $this->hasMany(Chat::class, 'receiver_id');
}
}