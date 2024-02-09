<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 *
 * @package App
 * @property string $category
*/
class Category extends Model
{
    protected $fillable = ['category',
    'normal_office_hours','punching',];
    
    
    
}
