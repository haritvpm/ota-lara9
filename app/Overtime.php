<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Overtime
 *
 * @package App
 * @property string $pen
* @property string $name
 * @property string $designation
 * @property string $form
 * @property string $from
 * @property string $to
 * @property integer $count
 * @property string $worknature
*/
class Overtime extends Model
{
    protected $fillable = ['pen', 'designation', 'from', 'to', 'count', 'worknature', 'form_id','rate', 'name',
    
    'punching', //whether punching is applicable to this employee
    'punchin', 'punchout', 'punching_id', 'employee_id','slots',
    'normal_office_hours', //this is not saved. just for backend validation
    'aadhaarid',//this is not saved. just for backend validation
    'overtimesittings' //for sitting days, each day of user is saved. 
];
    

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
    
    public function form($sortby=null)
    {
        
        return $this->belongsTo(Form::class, 'form_id');
               

    }
    public function employee($sortby=null)
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function overtimesittings(): HasMany
    {
        return $this->hasMany(OvertimeSitting::class);
    }
    public function getPENNameAttribute()
    {
        if($this->name){
            return $this->pen . "-" . $this->name;
        }

        return $this->pen; //old form
    }

    public function getNameOnlyAttribute()
    {
        $hiphen = strpos($this->pen, '-');

        if($hiphen !== false){
            return substr($this->pen, $hiphen+1);
        }

        return $this->name; //old form
    }
    


    
}
