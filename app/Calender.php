<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Class Calender
 *
 * @package App
 * @property string $date
 * @property enum $day_type
 * @property string $session
*/
class Calender extends Model
{
    protected $fillable = ['date', 'day_type', 'description', 'session_id', 'daylength_multiplier',  'punching',
];

public const PUNCHING_SELECT = [
    'MANUALENTRY'         => 'MANUALENTRY', ////allow edit. only some people punched. so let them enter it all
    'AEBAS'               => 'AEBAS', //// full data received. enforce punching
    'AEBAS_FETCH_PENDING' => 'AEBAS_FETCH_PENDING',
    'NOPUNCHING'          => 'NOPUNCHING', ////NIC server was down completely. dont enforce punching for office
];
    public static $enum_day_type = ["Sitting day" => "Sitting day", "Prior holiday" => "Prior holiday", "Prior Working day" => "Prior Working day", "Holiday" => "Holiday", "Intervening saturday" => "Intervening saturday", "Intervening Working day" => "Intervening Working day"];

    /**
     * Set attribute to date format
     * @param $input
     */
    public function setDateAttribute($input)
    {
        if ($input != null && $input != '') {
            $this->attributes['date'] = Carbon::createFromFormat(config('app.date_format'), $input)->format('Y-m-d');
        } else {
            $this->attributes['date'] = null;
        }
    }

    /**
     * Get attribute from date format
     * @param $input
     *
     * @return string
     */
    public function getDateAttribute($input)
    {
        $zeroDate = str_replace(['Y', 'm', 'd'], ['0000', '00', '00'], config('app.date_format'));

        if ($input != $zeroDate && $input != null) {
            return Carbon::createFromFormat('Y-m-d', $input)->format(config('app.date_format'));
        } else {
            return '';
        }
    }

    /**
     * Set to null if empty
     * @param $input
     */
    public function setSessionIdAttribute($input)
    {
        $this->attributes['session_id'] = $input ? $input : null;
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }
    public function getIsHolidayAttribute()
    {
        return str_contains($this->day_type,'oliday');
        
    }

}
