<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DesignationsOther
 *
 * @package App
 * @property string $designation
 * @property integer $rate

*/
class DesignationsOther extends Model
{
    protected $fillable = ['designation', 'rate','user_id', 'max_persons'];
    

    /**
     * Set attribute to money format
     * @param $input
     */
    public function setRateAttribute($input)
    {
        $this->attributes['rate'] = $input ? $input : null;
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
