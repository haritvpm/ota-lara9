<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;


/**
 * Class Form
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
class Form extends Model
{
    protected $fillable = ['session', 'creator', 'owner', 
    'form_no', 'overtime_slot', 'duty_date', 'date_from', 'date_to',
'submitted_by', 'submitted_names', 'submitted_on','remarks','updated_at'];
    

    public static $enum_overtime_slot = ["First" => "First", "Second" => "Second", "Third" => "Third", "Sittings" => "Sittings", "Additional" => "Additional" ];

    public function day_type()
    {
        if($this->duty_date == null){
            return null;// a sitting form
        }

           
        $session = \App\Session::where('name', $this->session )->first(); 

        //dd($session->calender()->get());

        $searchdate = Carbon::createFromFormat(config('app.date_format'), $this->duty_date )->format('Y-m-d');


        $day_type = $session->calender()->where( 'date', $searchdate)->first()->day_type;

        switch ($day_type) {
            case 'Sitting day': return 'S';
                
            case 'Prior holiday':
            case 'Holiday':
             
                return 'H';    
            
            default:
                return 'W';        
                
        }

        
    }



    public function overtimes()
    {
        return $this->hasMany(Overtime::class);
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

                      //Note:if we have similar usernames in submitted by. people with similar usernames can see each other's submitted items

    }

    public function scopefilterStatus($query, $status)
    {
        if ($status == 'todo') {
            return $query->whereRaw('creator = owner') //draft
                         ->orWhere(function ($query) { //to approve
                                $query->whereRaw('creator != owner')
                                      ->where('owner',Auth::user()->username);
                          });
                         
        }
        else if (strpos($status, 'Sent') === 0) {
            return $query->whereRaw('creator != owner')
                         ->where('owner', '!=', Auth::user()->username); //it is sent by me. no longer with me

        }
        else if ($status == 'Draft') {
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

    public function scopefilterDate($query, $datefilter, $dates=null)
    {

        if( $dates != null)
        {

          //dd($dates);

          $dates->transform(function ($item, $key) {
                return Carbon::createFromFormat(config('app.date_format'), $item )->format('Y-m-d');
          });

         // dd($dates->toArray());

          return $query->wherein( 'duty_date', $dates->toArray() );
                      
        }


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
        if($this->submitted_names != null){

            //ignore the first item which is normally the creators name            
            $tmp = trim($this->submitted_names,"|, ");

            $firstcoma = strpos($tmp, '|');

            if( $firstcoma === false ){
                return null;
            }

            // $submittedby_names =  str_replace("|", ", ", substr($tmp, $firstcoma + 1));

            //using comas too to prevent names here initials are like this
            //ex:-Accounts D|JS Ushus AS, AS
            //Accounts D|Ushus US, US Test|JS Ushus AS, AS

            $submittedby_names =  substr($tmp, $firstcoma + 1);

            //currently US in this wont be replaced
            //US Test|JS Ushus AS, AS as it is at beginin
            $submittedby_names = str_replace(' US ', ' Under Secretary ', $submittedby_names);


            $submittedby_names = str_replace(' DS ', ' Deputy Secretary ', $submittedby_names);

            $submittedby_names =  str_replace(', JS', ', Joint Secretary', $submittedby_names);

            $submittedby_names =  str_replace(', AS', ', Addl. Secretary', $submittedby_names);

            $submittedby_names =  str_replace(', SS', ', Special Secretary', $submittedby_names);
        }

        return $submittedby_names;
         
    }

    public function getSubmitedbyNameAttribute()
    {
        $submittedby_name = null;
        if($this->submitted_names != null){
            $tmp = explode( "|", $this->submitted_names);
            $submittedby_name = end($tmp);
            
        }

        return $submittedby_name;
         
    }
    public function getFirstSubmitedbyNameAttribute()
    {
        $submittedby_name = null;
        if($this->submitted_names != null){
            $tmp = explode( "|", $this->submitted_names);
            $submittedby_name = reset($tmp);
            
        }

        return $submittedby_name;
         
    }

    
    public function getOwnedbyNameAttribute()
    {
        $ownderby_name = null;
        if($this->owner != null){
            
            $username = \App\User::where('username',$this->owner)->first();
            
            if($username != null){
                $ownderby_name =  $username->Title;
                if($username->displayname != '') {
                    $ownderby_name = $username->displayname . ', ' . $ownderby_name;
                }
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
    public function getCreatorSectionAttribute()
    {
        if( 0 === strpos($this->creator,'de.'))
            return  substr($this->creator,3);

         return  $this->creator;
    }
    public function getisSameSectionCreatorAttribute()
    {
        $loggedusername = auth()->user()->username;
        if( 0 === strpos($loggedusername,'de.'))
            $loggedusername =  substr($loggedusername,3);
        
        return 0== strcasecmp($loggedusername, $this->CreatorSection);
    }
}
