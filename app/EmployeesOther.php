<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EmployeesOther
 *
 * @package App
 * @property enum $srismt
 * @property string $name
 * @property string $pen
 * @property string $designation
 * @property string $department_idno
 * @property string $added_by
 * @property enum $account_type
 * @property string $ifsc
 * @property string $account_no
*/
class EmployeesOther extends Model
{

    protected $fillable = ['srismt', 'name', 'pen', 'department_idno', 'account_type', 'ifsc', 'account_no', 'mobile', 'designation_id', 'added_by'];
    
    
    public static $enum_srismt = ["Sri" => "Sri", "Smt" => "Smt", "Kum" => "Kum"];

    public static $enum_account_type = ["Bank Account" => "Bank Account", "TSB" => "TSB"];

    /**
     * Set to null if empty
     * @param $input
     */
    public function setDesignationIdAttribute($input)
    {
        $this->attributes['designation_id'] = $input ? $input : null;
    }

    /**
     * Set to null if empty
     * @param $input
     */
    public function setAddedByIdAttribute($input)
    {
        $this->attributes['added_by_id'] = $input ? $input : null;
    }
    
    public function designation()
    {
        return $this->belongsTo(DesignationsOther::class, 'designation_id');
    }
    
    public function added_by()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    
}
