<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use Auth;

/**
 * Class FormOther
 *
 * @package App
 * @property string $session
 * @property string $creator
 * @property string $owner
 * @property integer $form_no
 * @property enum $overtime_slot
 * @property string $duty_date
 * @property string $date_from
 * @property string $date_to
*/
class FormOther extends Model
{
    protected $fillable = ['session', 'creator', 'owner', 'form_no', 'overtime_slot', 'duty_date', 'date_from', 'date_to','submitted_by','submitted_on','remarks','updated_at' ];
    

    public static $enum_overtime_slot = ["First" => "First", "Second" => "Second", "Third" => "Third", "Sittings" => "Sittings"];

  

    public function overtimes()
    {
        return $this->hasMany(OvertimeOther::class,'form_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class,'creator','username');
    }
    public function owned_by()
    {
        return $this->belongsTo(User::class,'owner','username');
    }

    public function session()
    {
        return $this->belongsTo(Session::class,'session','name');
    }
  
    public function scopeCreatedOrOwnedByLoggedInUser($query)
    {
        return  $query->where('owner',Auth::user()->username)
                      ->Orwhere('creator',Auth::user()->username);
    }

    public function scopeCreatedOrOwnedOrApprovedByLoggedInUser($query)
    {
        if(Auth::user()->isAdminorAudit()){
            return $query;
        }
        
        return  $query->where('owner',Auth::user()->username)
                      ->Orwhere('creator',Auth::user()->username)
                      ->OrWhere('submitted_by','like','%'.Auth::user()->username.'%');
    }

    public function scopefilterStatus($query, $status)
    {
        if ($status == 'Draft') {
            return $query->whereRaw('creator = owner');
            
        }
        else if ($status == 'To_approve') {
            return $query->whereRaw('creator != owner')->where('owner',Auth::user()->username);
                
        }
        else if (strpos($status, 'Pending') === 0) {
            return $query->whereRaw('creator != owner')
                ->where('owner', '!=', 'admin')
                ->where('owner', '!=', Auth::user()->username); //it is sent by me. no longer with me

        }
        else if ($status == 'Submitted') {
            return $query->where('owner', 'admin');
        }

    }

    public function scopefilterDate($query, $datefilter)
    {
        $pieces = explode("-", $datefilter );

        $searchstring = $datefilter;
        
        if( count($pieces) == 3 )
        {
            $searchdate = Carbon::createFromFormat(config('app.date_format'), $datefilter )->format('Y-m-d');
            
            return $query->where( 'duty_date', $searchdate )
                           ->orWhere( function($q) use ($searchdate) {
                                $q->where('date_from','<=',  $searchdate)
                                ->where('date_to','>=',  $searchdate);
                           });

        }
        else
        {
            if( count($pieces) == 2 ) //swap month and date or year and month
            {
                $searchstring = str_pad($pieces[1],2,"0",STR_PAD_LEFT) . '-' . str_pad($pieces[0],2,"0",STR_PAD_LEFT);    
            }


            return  $query->where('duty_date','like', '%'. $searchstring . '%' )
                        ->orWhere('date_from','like', '%'. $searchstring . '%' )
                        ->orWhere('date_to','like', '%'. $searchstring . '%' );
        }

    }

    public function getSubmitedbyNamesAttribute()
    {
        $submittedby_names= null;
        if($this->submitted_by != null){
            $submittedby = explode( ",", $this->submitted_by);
            // foreach($submittedby as $user) {
            //     $user = trim($user);

            //     $username = \App\User::where('username',$user)->get()->first();
            //     $submittedby_names .=  $username->displayname != '' ? $username->displayname : $username->name . ', ' ;
            
            // }  


            
            $usernames = \App\User::wherein('username',$submittedby)->get();

            foreach($usernames as $user) {
                
                $submittedby_names .=  $user->displayname != '' ? $user->displayname : $user->name . ', ' ;
            }
                                                  

            $submittedby_names = trim($submittedby_names,", ");
        }

        return $submittedby_names;
         
    }
    public function getSubmitedbyNameAttribute()
    {
        $submittedby_name = null;
        if($this->submitted_by != null){
            $tmp = explode( ",", $this->submitted_by);
            $submittedby = end($tmp);
            
            $username = \App\User::where('username',trim($submittedby))->first();
            
            $submittedby_name =  $username->name;
            if($username->displayname != '') {
                $submittedby_name = $username->displayname . ', ' . $submittedby_name;
            }

        }

        return $submittedby_name;
         
    }

    public function getOwnedbyNameAttribute()
    {
        $ownderby_name = null;
        if($this->owner != null){
            
            $username = \App\User::where('username',$this->owner)->first();
            
            $ownderby_name =  $username->name;
            if($username->displayname != '') {
                $ownderby_name = $username->displayname . ', ' . $ownderby_name;
            }

        }

        return $ownderby_name;
         
    }


    /**
     * Set attribute to money format
     * @param $input
     */
    public function setFormNoAttribute($input)
    {
        $this->attributes['form_no'] = $input ? $input : null;
    }

    /**
     * Set attribute to date format
     * @param $input
     */
    public function setDutyDateAttribute($input)
    {
        if ($input != null && $input != '') {
            $this->attributes['duty_date'] = Carbon::createFromFormat(config('app.date_format'), $input)->format('Y-m-d');
        } else {
            $this->attributes['duty_date'] = null;
        }
    }

    /**
     * Get attribute from date format
     * @param $input
     *
     * @return string
     */
    public function getDutyDateAttribute($input)
    {
        $zeroDate = str_replace(['Y', 'm', 'd'], ['0000', '00', '00'], config('app.date_format'));

        if ($input != $zeroDate && $input != null) {
            return Carbon::createFromFormat('Y-m-d', $input)->format(config('app.date_format'));
        } else {
            return '';
        }
    }

    /**
     * Set attribute to date format
     * @param $input
     */
    public function setDateFromAttribute($input)
    {
        if ($input != null && $input != '') {
            $this->attributes['date_from'] = Carbon::createFromFormat(config('app.date_format'), $input)->format('Y-m-d');
        } else {
            $this->attributes['date_from'] = null;
        }
    }

    /**
     * Get attribute from date format
     * @param $input
     *
     * @return string
     */
    public function getDateFromAttribute($input)
    {
        $zeroDate = str_replace(['Y', 'm', 'd'], ['0000', '00', '00'], config('app.date_format'));

        if ($input != $zeroDate && $input != null) {
            return Carbon::createFromFormat('Y-m-d', $input)->format(config('app.date_format'));
        } else {
            return '';
        }
    }

    /**
     * Set attribute to date format
     * @param $input
     */
    public function setDateToAttribute($input)
    {
        if ($input != null && $input != '') {
            $this->attributes['date_to'] = Carbon::createFromFormat(config('app.date_format'), $input)->format('Y-m-d');
        } else {
            $this->attributes['date_to'] = null;
        }
    }

    /**
     * Get attribute from date format
     * @param $input
     *
     * @return string
     */
    public function getDateToAttribute($input)
    {
        $zeroDate = str_replace(['Y', 'm', 'd'], ['0000', '00', '00'], config('app.date_format'));

        if ($input != $zeroDate && $input != null) {
            return Carbon::createFromFormat('Y-m-d', $input)->format(config('app.date_format'));
        } else {
            return '';
        }
    }
   /* function array_md5(Array $array) {
        //since we're inside a function (which uses a copied array, not 
        //a referenced array), you shouldn't need to copy the array
        array_multisort($array);
        return md5(json_encode($array));
    }*/
    
    public function getMD5Attribute()
    {
        $ot = $this->overtimes()->get();
        $str = $this->session . $this->overtime_slot . $this->DutyDate . $this->DateFrom . $this->DateTo ;
        foreach ($ot as $o) {
         $str .= $o->pen . $o->designation . $o->from . $o->to 
            . $o->count . $o->count . optional($o->employeesother)->ifsc  .  optional($o->employeesother)->account_no ;

        };

        return md5($str); 

    }
    
}
