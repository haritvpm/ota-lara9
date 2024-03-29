<?php

namespace App;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//successattendance api
class Punching extends Model
{
    use HasFactory;

    public $table = 'punchings';

    // protected $dates = [
    //     'date',
    //     'created_at',
    //     'updated_at',
    //     'deleted_at',
    // ];

    protected $fillable = [
        
        'pen',
        'name',
        'date',
        'punch_in',
        'punch_out',
             
        'created_at',
        'updated_at',
        'deleted_at',
        'aadhaarid',
        'creator',
        'punchin_from_aebas', 'punchout_from_aebas',
      
        'in_device',
        'in_time',
        'out_device',
        'out_time',
        'at_type',

       // 'allowpunch_edit',
    ];

    // protected function serializeDate(DateTimeInterface $date)
    // {
    //     return $date->format('Y-m-d H:i:s');
    // }

  

   



 /**
     * Set attribute to date format
     * @param $input
     */
    /*
     public function setDateAttribute($input)
    {
        if ($input != null && $input != '') {
            $this->attributes['date'] = Carbon::createFromFormat(config('app.date_format'), $input)->format('Y-m-d');
        } else {
            $this->attributes['date'] = null;
        }
    }
   */

    /**
     * Get attribute from date format
     * @param $input
     *
     * @return string
     */
    /*
    public function getDateAttribute($input)
    {
        $zeroDate = str_replace(['Y', 'm', 'd'], ['0000', '00', '00'], config('app.date_format'));

        if ($input != $zeroDate && $input != null) {
            return Carbon::createFromFormat('Y-m-d', $input)->format(config('app.date_format'));
        } else {
            return '';
        }
    }*/

   
}