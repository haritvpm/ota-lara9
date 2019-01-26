<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Routing
 *
 * @package App
 * @property string $user
 * @property string $route
*/
class Routing extends Model
{
    protected $fillable = ['route', 'user_id', 'last_forwarded_to'];
    

    /**
     * Set to null if empty
     * @param $input
     */
    public function setUserIdAttribute($input)
    {
        $this->attributes['user_id'] = $input ? $input : null;
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function forwardable_usernames()
    {
        $myusername = $this->user->username;

        $forwardables = collect();

        //see if we have a route defined. if not, get all possibles
        $userstarts =  explode(',', $this->route);
        if( count($userstarts) != 0 && $this->route != ''){
            //route like 
             $forwardables = $this->user->getUsernamesStartingWith($userstarts)
                             ->pluck('username');
            return $forwardables->unique()->except($myusername); //no need to forward to us
        }
        
        $namestarts = array( 'sn', 'us', 'ds', 'js', 'as','ss');
        $myname = $this->user->Title;
         //if we are  a section, we need every user above us
        if( 0 === strpos( $myusername, 'sn.' )){
            $myname = 'sn';
        }

        //If our name is 'US 2 Accounts', we take US
       
        $mynamestart = strtolower (substr($myname, 0, 2));


        //we find the position of our own name start in the namestarts array. then we take the rest to load
        
        for ($i=0; $i <  count($namestarts)-1 ; $i++) { //no need for SS to forward
           if( 0 === strpos( $mynamestart, $namestarts[$i] ) ){

            $forwardables = $this->user->getUsernamesWithNameStartingWith( array_slice( $namestarts, $i+1 )  )
                                        ->pluck('username');

            break;
           }

        }
        
        return $forwardables->unique()->except($myusername); //no need to forward to us
    }

    public function cansubmit_to_accounts( $slot )
    {
        $cansubmittoaccounts = false;
        $cansubmit = null;
        $loggedinusername = $this->user->username;
        $loggedinname = $this->user->Title;
      
        //only ds and above can submit 2nd and 3rd OT on sitting days
        //and any OT of NS days
        if( $slot  == 'Sittings'){
            $cansubmit = array('us', 'ds', 'oo','chief', 'librarian' );
        }
        else{
            $cansubmit = array('ds', 'oo','chief' );
        }

        foreach ($cansubmit as $val) {
            if (0 === strpos($loggedinusername, $val . '.')){
                $cansubmittoaccounts = true; break;
            }
        }
        

        //can also submit if name is equal to this
        //or starts with this and space
        if( $slot  == 'Sittings'){
            $cansubmit = array( 'us','ds', 'js','as','ss' );
        }
        else{
            $cansubmit = array('ds', 'js','as','ss' );
        }
       
        //strcasecmp —  case-insensitive

        //if not data entry
        if(0 !== strpos($loggedinusername, 'de.')){
            foreach ($cansubmit as $val) {
                if ( strcasecmp($loggedinname,$val)== 0  || 
                    strncasecmp($loggedinname,$val . ' ',3 ) == 0
                    ){
                    $cansubmittoaccounts = true; break;
                }

            }
        }


        return $cansubmittoaccounts;
    }
    
}
