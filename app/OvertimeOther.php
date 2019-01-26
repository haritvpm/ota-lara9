<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OvertimeOther
 *
 * @package App
 * @property string $pen
 * @property string $designation
 * @property string $from
 * @property string $to
 * @property integer $count
 * @property string $worknature
*/
class OvertimeOther extends Model
{
    protected $fillable = ['pen', 'designation', 'from', 'to', 'count', 'worknature', 'form_id','rate'];
    

    /**
     * Set to null if empty
     * @param $input
     */
    public function setFormIdAttribute($input)
    {
        $this->attributes['form_id'] = $input ? $input : null;
    }

    /**
     * Set attribute to money format
     * @param $input
     */
    public function setCountAttribute($input)
    {
        $this->attributes['count'] = $input ? $input : null;
    }
    
    public function form()
    {
        return $this->belongsTo(FormOther::class, 'form_id');
    }
    public function employeesother()
    {
        return $this->belongsTo(EmployeesOther::class, 'designation','pen');
    }
    
}
