<?php

namespace App;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PunchingTrace extends Model
{
    use HasFactory;

    public $table = 'punching_traces';

    protected $dates = [
      //  'att_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'aadhaarid',
        'org_emp_code',
        'device',
        'attendance_type',
        'auth_status',
        'err_code',
        'att_date',
        'att_time',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // protected function serializeDate(DateTimeInterface $date)
    // {
    //     return $date->format('Y-m-d H:i:s');
    // }

    // public function getAttDateAttribute($value)
    // {
    //     return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    // }

    // public function setAttDateAttribute($value)
    // {
    //     $this->attributes['att_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    // }
}
