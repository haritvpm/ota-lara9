<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Exemption
 *
 * @package App
 * @property string $pen
 * @property string $designation
 * @property string $worknature
 * @property string $exemptionform
*/
class Exemption extends Model
{
    protected $fillable = ['pen', 'name',  'designation', 'worknature', 'exemptionform_id'];
    
    

    /**
     * Set to null if empty
     * @param $input
     */
    public function setExemptionformIdAttribute($input)
    {
        $this->attributes['exemptionform_id'] = $input ? $input : null;
    }
    
    public function form()
    {
        return $this->belongsTo(Exemptionform::class, 'exemptionform_id');
    }
    
}
