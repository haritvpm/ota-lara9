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
    protected $fillable = ['designation', 'rate'];
    

    /**
     * Set attribute to money format
     * @param $input
     */
    public function setRateAttribute($input)
    {
        $this->attributes['rate'] = $input ? $input : null;
    }
    
}
