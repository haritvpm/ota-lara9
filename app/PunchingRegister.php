<?php

namespace App;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PunchingRegister extends Model
{
    use HasFactory;

    public $table = 'punching_registers';

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const FLEXI_SELECT = [
        'yes' => 'YES',
        'no'  => 'NO',
        'na'  => 'N/A',
    ];

    protected $fillable = [
        'date',
        'employee_id',
        'punchin_id',
        'duration',
        'flexi',
        'grace_min',
        'extra_min',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function punchin()
    {
        return $this->belongsTo(Punching::class, 'punchin_id');
    }
}
