<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Preset
 *
 * @package App
 * @property string $user
 * @property string $name
 * @property text $pens
*/
class Preset extends Model
{
    protected $fillable = ['name', 'pens', 'user_id'];
    

    /**
     * Set to null if empty
     * @param $input
     */
    public function setUserIdAttribute($input)
    {
        $this->attributes['user_id'] = $input ? $input : null;
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
