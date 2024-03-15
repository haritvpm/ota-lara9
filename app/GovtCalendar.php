<?php

namespace App;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovtCalendar extends Model
{
    use HasFactory;

    public $table = 'govt_calendars';

    protected $dates = [
        'date',
        'success_attendance_lastfetchtime',
        'attendancetodaytrace_lastfetchtime',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'date',
        'govtholidaystatus',
        'restrictedholidaystatus',
        'bankholidaystatus',
        'festivallist',
        'success_attendance_fetched',
        'success_attendance_lastfetchtime',
        'success_attendance_rows_fetched',
        'attendance_today_trace_fetched',
        'attendancetodaytrace_lastfetchtime',
        'attendance_today_trace_rows_fetched',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /*public function getDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }*/

    public function getSuccessAttendanceLastfetchtimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setSuccessAttendanceLastfetchtimeAttribute($value)
    {
        $this->attributes['success_attendance_lastfetchtime'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getAttendancetodaytraceLastfetchtimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setAttendancetodaytraceLastfetchtimeAttribute($value)
    {
        $this->attributes['attendancetodaytrace_lastfetchtime'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }
}
