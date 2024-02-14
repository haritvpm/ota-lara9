<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Employee
 *
 * @package App
 * @property string $name
 * @property string $pen
 * @property string $designation
*/
class Employee extends Model
{
    protected $fillable = ['srismt', 'name',  'pen', 'category', 'designation_id',  'added_by', 'desig_display', 'categories_id','aadhaarid','created_at'];
   
    public static $enum_srismt = ["Sri" => "Sri", "Smt" => "Smt", "Kum" => "Kum"];

    public static $enum_category_admin = ["Staff" => "Staff", "Provisional" => "Provisional", "Staff - Admin Data Entry" => "Staff - Admin Data Entry","Relieved" => "Relieved" ];

    public static $enum_category = ["Staff" => "Staff", "Provisional" => "Provisional"];

    public $timestamps = true;

    /**
     * Set to null if empty
     * @param $input
     */
    public function setDesignationIdAttribute($input)
    {
        $this->attributes['designation_id'] = $input ? $input : null;
    }
    
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

 
    public function categories()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    public function getPENNameAttribute()
{
    return $this->pen . ' - ' . $this->name;
}
    
}
