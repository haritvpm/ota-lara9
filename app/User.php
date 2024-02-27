<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPassword;
use Hash;

/**
 * Class User
 *
 * @package App
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $remember_token
 * @property string $username
*/
class User extends Authenticatable
{
    use Notifiable;
    protected $fillable = ['name', 'email', 'password', 'remember_token', 'username', 'displayname','role_id'];
    
    
    /**
     * Hash password
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input)
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
    }
    

    /**
     * Set to null if empty
     * @param $input
     */
    public function setRoleIdAttribute($input)
    {
        $this->attributes['role_id'] = $input ? $input : null;
    }
    
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    
    public function routing()
    {
        return $this->hasOne('\App\Routing');
    }

    public function isAdmin() 
    {
       return $this->role_id == 1;
    }
    public function isAdminorAudit() 
    {
       return $this->role_id == 1 || $this->role_id == 5;
    }
    public function isAudit() 
    {
       return $this->role_id == 5;
    }
    public function isSimpleUser() 
    {
       return $this->role_id == 2;
    }
    public function scopeSimpleUsers($query)
    {
        return $query->where('role_id', '=', 2);
    }
    
    public function scopeNotSimpleAndHiddenUsers($query)
    {
         return $query->where('role_id', '<>', 2)//simple user
                  ->where('role_id', '<>', 7);  //hidden
    }
     public function scopeSimpleOrHiddenUsers($query)
    {
         return $query->where('role_id', '=', 2)//simple user
                  ->orwhere('role_id', '=', 7);  //hidden
    }

     public function scopeHiddenUsers($query)
    {
        return $query->where('role_id', '=', 7);
    }
     public function scopeOtherDeptUsers($query)
    {
        return $query->where('role_id', '=', 3);
    }
    public function isServices() 
    {
       return $this->role_id == 6;
    }
    public function isOD() 
    {
       return $this->role_id == 3;
    }
    public function isHidden() 
    {
       return $this->role_id == 7;
    }
     
    public function isThirdOTAllowed( $formcreator=null) 
    {
         //we should set parttime even if it is being edited by house keeping
        if($formcreator){
            if(false !== strpos($formcreator, 'health') || 
               false !== strpos($formcreator, 'agri' ) ||
               false !== strpos($formcreator, 'sn.am') || 
               false !== strpos($formcreator, 'sn.ma')
            ){
               return false ;       
            }
        }
                
        if( false !== strpos( $this->username, 'health' ) || 
            false !== strpos( $this->username, 'agri') || 
            false !== strpos( $this->username, 'sn.am') || 
            false !== strpos( $this->username, 'sn.ma')
            ){
                return false ;        
        }

        return true;        
    }

    public function isDataEntryLevel() 
    {
       // return 0 === strpos( $this->username  , 'sn.') || 0 === strpos( $this->username  , 'od.') || 0 === strpos( $this->username  , 'de.');

        return 0 === strpos( $this->username  , 'od.') || 0 === strpos( $this->username  , 'de.');
    }
    public function isSectionOfficer() 
    {
        return 0 === strpos( $this->username  , 'sn.');
    }
    public function isDSorAbove() 
    {
        return 0 === strpos( $this->username  , 'ds.') || 
        $this->isFinalLevel() || $this->isJSorASorSSLevel();
    }
    public function isFinalLevel() 
    {
        if($this->isDataEntryLevel() )
            return false;
        
        return 0 === strpos( $this->username  , 'oo.') || 0 == strncasecmp( $this->name  , 'SS',2);
    }


    public function isJSorASorSSLevel() 
    {
        if($this->isDataEntryLevel() )
            return false;
       
        return 0 == strncasecmp( $this->name  , 'SS',2) || 
        0 == strncasecmp( $this->name  , 'AS',2) ||
        0 == strncasecmp( $this->name  , 'JS',2) ;
    }   
    

    public function sendPasswordResetNotification($token)
    {
       $this->notify(new ResetPassword($token));
    }

    public function getUsernamesStartingWith(array $keywords = array())
    {
    
        //we can ignore users like other departments 
        $result = \App\User::where( 'role_id', $this->role_id )
            ->where('username', 'not like', 'de.%') 
            //->where('name', 'not like', '%hidden%') //just change the role to something else
            ->where(function($query) use ($keywords){
                 foreach($keywords as $keyword){
                    //orWhere('username', 'LIKE', "$keyword%")
                    //not dependent on usernames. only on names
                      $query = $query->orWhere('username', 'LIKE', "$keyword%") //to get us., ds.
                                     ->orWhere('name', $keyword); //to get JS, 

                 }
                 return $query;
            });

        return $result->orderby('name','asc')->get(['username']); // at this line the query will be executed only
                               // after it was built in the last few lines
    }
    public function getUsernamesWithNameStartingWith(array $keywords = array())
    {
   
        //we can ignore users like other departments 
        $result = \App\User::where( 'role_id', $this->role_id )
            ->where('username', 'not like', 'de.%')
            ->where('username', 'not like', 'sn.%') 
            //->where('name', 'not like', '%hidden%')
            ->where(function($query) use ($keywords){
                 foreach($keywords as $keyword){
                   
                      $query = $query->orWhere('name', 'like', $keyword . '%'); //to get JS, 

                 }
                 return $query;
            });

        return $result->orderby('name','asc')->get(['username']); // at this line the query will be executed only
                               // after it was built in the last few lines
    }
    public function getTitleAttribute()
    {
      return strstr($this->name, '|', true) ?: $this->name;
    }
    /*public function getNameAttribute($value)
    {     
      return strstr($value, '|', true) ?: $value;
      // return strstr($this->name, '|', true) ?: $this->name;
    }*/

    public function getTitleFullAttribute()
    {   
        //$this->attributes['name']
        
        $name = $this->Title;

        //as exising names in db was just js (2 chars)
        //we need to compare first two chars only for js as ss
        if( 0 == strncmp( $name, 'JS',2)){
            $name = 'Joint Secretary';
        }else if( 0 == strncmp( $name, 'AS',2)){
            $name = 'Addl. Secretary';
        }else if( 0 == strncmp( $name, 'SS',2)){
            $name = 'Special Secretary';
        }
        else if( 0 == strncasecmp( $name, 'us ',3)){
            $name = 'Under Secretary ' . substr($name,3);
        }else if( 0 == strcasecmp( $name, 'us')){
            $name = 'Under Secretary';
        }else if( 0 == strncasecmp( $name, 'ds ',3)){
            $name = 'Deputy Secretary '. substr($name,3);
        }else if( 0 == strcasecmp( $name, 'ds')){
            $name = 'Deputy Secretary';
        }

        return $name;
         
    }


    public function getDispNameWithNameAttribute()
    {   
        //$this->attributes['name']
        
        $name = $this->TitleFull;
        
        if($this->displayname != '') {
           return $this->displayname . ', ' . $name;
        }

        return $name;
         
    }
    public function getDispNameWithNameShortAttribute()
    {   
        //$this->attributes['name']
        
        $name = $this->Title;

        //as exising names in db was just js (2 chars)
        //we need to compare first two chars only for js as ss
        if( 0 == strncasecmp( $name, 'js',2)){
            $name = 'JS';
        }else if( 0 == strncmp( $name, 'AS',2)){ //asst librarian
            $name = 'AS';
        }else if( 0 == strncasecmp( $name, 'ss',2)){
            $name = 'SS';
        }
       

        if($this->displayname != '') {
            //this space followed by coma is important
           return $this->displayname . ', ' . $name;
        }

        return $name;
         
    }

    public static function needsPostingOrder($user)
    {
        $needsposting = true;

        //we should set parttime even if it is being edited by house keeping

        if(false !== strpos( $user, 'health') || 
            false !== strpos( $user, 'agri' )){
            $needsposting = false;        
        }

        if(false !== strpos($user, 'watchnward')){
            $needsposting = false;
        }

        if(false !== strpos($user, 'sn.am') /*|| 
          false !== strpos($user, 'sn.ma')*/){
            $needsposting = false;
        }


        if( false !== strpos(  $user, 'oo.') ){
            $needsposting = false; //dyspkr and sec too
        }

        return $needsposting ;
        
    }

}
