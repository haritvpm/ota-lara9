<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Session
 *
 * @package App
 * @property string $name
 * @property integer $kla
 * @property integer $session
 * @property enum $dataentry_allowed
 * @property enum $show_in_datatable
* @property enum $sittings_entry
*/
class Session extends Model
{
    protected $fillable = ['name', 'kla', 'session', 'dataentry_allowed', 'show_in_datatable', 'sittings_entry'];


    public static $enum_dataentry_allowed = ["Yes" => "Yes", "No" => "No"];

    public static $enum_show_in_datatable = ["Yes" => "Yes", "No" => "No"];
 public static $enum_sittings_entry = ["Yes" => "Yes", "No" => "No"];
    /**
     * Set attribute to money format
     * @param $input
     */
    public function setKlaAttribute($input)
    {
        $this->attributes['kla'] = $input ? $input : null;
    }

    /**
     * Set attribute to money format
     * @param $input
     */
    public function setSessionAttribute($input)
    {
        $this->attributes['session'] = $input ? $input : null;
    }

    public function calender()
    {
        return $this->hasMany('App\Calender');
    }
    public function attendances()
    {
        return $this->hasMany('App\Attendance');
    }

    // A function to return the Roman Numeral, given an integer 
    public function numberToRoman($num)  
    { 
     // Make sure that we only use the integer portion of the value 
     $n = intval($num); 
     $result = ''; 

     // Declare a lookup array that we will use to traverse the number: 
     $lookup = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 
     'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 
     'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1); 

     foreach ($lookup as $roman => $value)  
     { 
         // Determine the number of matches 
         $matches = intval($n / $value); 

         // Store that many characters 
         $result .= str_repeat($roman, $matches); 

         // Substract that from the number 
         $n = $n % $value; 
     } 

     // The Roman numeral should be built, return it 
     return $result; 
    } 

    public function getRomanKLA($suffix = true)
    {
        if($suffix){
            return $this->numberToRoman($this->kla) .  '<sup>' .  $this->getOrdinalSuffix($this->kla) . '</sup>' ;
        }
        
        return $this->numberToRoman($this->kla) ;

    }
    
    public function getOrdinalSuffix($number)
    {
        $number = abs($number) % 100;
        $lastChar = substr($number, -1, 1);
        switch ($lastChar) {
            case '1' : return ($number == '11') ? 'th' : 'st';
            case '2' : return ($number == '12') ? 'th' : 'nd';
            case '3' : return ($number == '13') ? 'th' : 'rd'; 
        }
        return 'th';  
    }

    public function getMalayalamOrdinalSuffix($number)
    {
       $arr = array('ഒന്നാം','രണ്ടാം','മൂന്നാം','നാലാം ',
                    'അഞ്ചാം','ആറാം','ഏഴാം','എട്ടാം',
                    'ഒമ്പതാം','പത്താം','പതിനൊന്നാം',
                    'പന്ത്രണ്ടാം','പതിമൂന്നാം',
                    'പതിനാലാം','പതിനഞ്ചാം', 
                    'പതിനാറാം','പതിനേഴാം','പതിനെട്ടാം',
                    'പത്തൊൻപതാം','ഇരുപതാം',
                    'ഇരുപത്തൊന്നാം','ഇരുപത്തിരണ്ടാം',
                    'ഇരുപത്തിമൂന്നാം','ഇരുപത്തിനാലാം',
                    'ഇരുപത്തിയഞ്ചാം','ഇരുപത്തിയാറാം',
                    'ഇരുപത്തിയേഴാം','ഇരുപത്തിയെട്ടാം');

       if( $number <= count($arr) ) 
            return $arr[$number-1];

         return $number . '-ാം';

    }


}
