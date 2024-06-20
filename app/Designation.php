<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Designation
 *
 * @package App
 * @property string $designation
 * @property integer $rate
*/
class Designation extends Model
{
    protected $fillable = ['designation', 'rate', 'punching',
    'normal_office_hours','type',
        'has_additional_ot',
        'office_time_id',];
    

    public const TYPE_SELECT = [
        'normal'   => 'normal',
        'fulltime' => 'fulltime',
        'parttime' => 'parttime',
    ];
    /**
     * Set attribute to money format
     * @param $input
     */
    public function setRateAttribute($input)
    {
        $this->attributes['rate'] = $input ? $input : null;
    }

    public function office_time()
    {
        return $this->belongsTo(OfficeTime::class, 'office_time_id');
    }
}
