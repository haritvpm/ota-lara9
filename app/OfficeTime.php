<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficeTime extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'office_times';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'groupname',
        'fn_from',
        'an_to',
        'minutes_for_ot_workingday',
        'minutes_for_ot_holiday',
        'max_ot_workingday',
        'max_ot_sittingday',
        'max_ot_holiday',
        'office_minutes',
        'sittingday_duration_min_for_second_ot',
        'workingday_duration_min_for_first_ot',
        'sitting_ot_time_str',
        'sitting_ot_initial_leeway_min',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
