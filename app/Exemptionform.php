<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

/**
 * Class Exemptionform
 *
 * @package App
 * @property string $session
 * @property string $creator
 * @property string $owner
 * @property integer $form_no
 * @property string $submitted_names
 * @property string $submitted_by
 * @property string $submitted_on
 * @property string $remarks
*/
class Exemptionform extends Model
{
    protected $fillable = ['session', 'creator', 'owner', 'form_no', 'submitted_names', 'submitted_by', 'submitted_on', 'remarks'];
    
    

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
    /*
    public function setSubmittedOnAttribute($input)
    {
        if ($input != null && $input != '') {
            $this->attributes['submitted_on'] = Carbon::createFromFormat(config('app.date_format'), $input)->format('Y-m-d');
        } else {
            $this->attributes['submitted_on'] = null;
        }
    }*/

    /**
     * Get attribute from date format
     * @param $input
     *
     * @return string
     */
    /*
    public function getSubmittedOnAttribute($input)
    {
        $zeroDate = str_replace(['Y', 'm', 'd'], ['0000', '00', '00'], config('app.date_format'));

        if ($input != $zeroDate && $input != null) {
            return Carbon::createFromFormat('Y-m-d', $input)->format(config('app.date_format'));
        } else {
            return '';
        }
    }*/

    public function exemptions()
    {
        return $this->hasMany(Exemption::class);
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
        if(Auth::user()->isAdminorAudit() || Auth::user()->isServices()){
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
    public function getCreatorSectionAttribute()
    {
        if( 0 === strpos($this->creator,'de.'))
            return  substr($this->creator,3);

         return  $this->creator;
    }

    
}
