<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class GroupSession extends Model
{
    use HasFactory;
    protected $fillable = [
        'group_id',
        'title',
        'description',
        'time',
        'link',
    ];

    /**
     * تحويل time تلقائيًا لـ Carbon datetime بدون تحويل المنطقة الزمنية
     */
    protected $casts = [
        'time' => 'datetime',
    ];

    /**
     * Accessor: عرض الوقت بشكل منسق بدون تحويل المنطقة الزمنية
     */
    public function getFormattedTimeAttribute()
    {
        return $this->time
            ? $this->time->translatedFormat('d M Y - h:i A')
            : null;
    }

    /**
     * Accessor: وقت انتهاء الجلسة (ساعة واحدة)
     */
    public function getEndTimeAttribute()
    {
        return $this->time ? $this->time->copy()->addHour() : null;
    }

    /**
     * Scope: الجلسات المباشرة
     */
    public function scopeLive($query)
    {
        $now = Carbon::now();
        return $query->where('time', '<=', $now)
                    ->whereRaw('DATE_ADD(time, INTERVAL 1 HOUR) > ?', [$now]);
    }

    /**
     * Scope: الجلسات القادمة
     */
    public function scopeUpcoming($query)
    {
        $now = Carbon::now();
        return $query->where('time', '>', $now);
    }

    /**
     * Scope: الجلسات المكتملة
     */
    public function scopeCompleted($query)
    {
        $now = Carbon::now();
        return $query->whereRaw('DATE_ADD(time, INTERVAL 1 HOUR) <= ?', [$now]);
    }

    /**
     * العلاقة مع المجموعة
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}


