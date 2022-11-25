<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Attendance
 *
 * @package App
 * @property string $session
 * @property string $employee
 * @property string $present_dates
*/
class Attendance extends Model
{
    protected $fillable = ['present_dates',
    'pen', 'name',
    'session_id',
    'total','employee_id','created_at',
    'updated_at',];
    public $table = 'attendances';

    /**
     * Set to null if empty
     * @param $input
     */
    public function setSessionIdAttribute($input)
    {
        $this->attributes['session_id'] = $input ? $input : null;
    }

    /**
     * Set to null if empty
     * @param $input
     */
    public function setEmployeeIdAttribute($input)
    {
        $this->attributes['employee_id'] = $input ? $input : null;
    }
    
    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }
    
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    
}
