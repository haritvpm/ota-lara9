<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 *
 * @package App
 * @property string $name
 * @property string $value
*/
class Setting extends Model
{
    protected $fillable = ['name', 'value'];
    
    
}
