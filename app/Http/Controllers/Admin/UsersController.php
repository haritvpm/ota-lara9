<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;


class UsersController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('user_access')) {
            return abort(401);
        }

        $nodisplnameusers = null;

        if(auth()->user()->isAdminorITAdmin()){
            $nodisplnameusers = User::wherenull('displayname'               )
                ->where('username','not like', 'de.%')
                ->where(function($query)
                {
                  $query->where('username','like', 'us.%')
                        ->orwhere('username','like', 'ds.%')
                        ->orwhere('username','like', 'sn.%')
                        ->orwhere('name','like', 'js%')
                        ->orwhere('name','like', 'as%');

                })->distinct()->get()->pluck('username');

          //JS promoted to AS but our Title still is JS
            $username2name = User::
                  where('username','not like', 'de.%')
                ->where(function($q)
                {
                  $q->where('name','like', 'js%')
                    ->orwhere('name','like', 'as%')
                    ->orwhere('name','like', 'ss%');

                })->distinct()->get()->pluck( 'name', 'username');

            //for each of this, find out PEN from title
            $pens = null;
            $username2pen = array();
            foreach ($username2name as $username => $name) {
                $x = strpos(  $name, '|' );
                if( false === $x )
                    continue;
                 $pen = trim(substr($name, $x+1));
                 if($pen != ''){
                     $username2pen[$username] = $pen;
                     $pens .=  $pen . ",";
                 }
            }

            $pens = trim( $pens, ","  );
           
            //find emp with this PEN

            $pen2desig = \App\Employee::with('designation')->wherein( 'pen', explode(',',$pens) )->get()->pluck('designation', 'pen');
            
            $conflictdesignempl = array();

            foreach ($username2pen as $username => $pen) {
                $desig = strtolower($pen2desig[$pen]->designation);
               
                if( $desig == 'joint secretary' && 
                    strncasecmp($username2name[$username], 'JS', 2) != 0 ){
                    array_push($conflictdesignempl,$username );
                }
                if( $desig == 'additional secretary' && 
                    strncasecmp($username2name[$username], 'AS', 2) != 0 ){
                    array_push($conflictdesignempl,$username );
                }
                if( $desig == 'special secretary' && 
                    strncasecmp($username2name[$username], 'SS', 2) != 0 ){
                    array_push($conflictdesignempl,$username );
                }

            }


        }
       
        $nolegsecttusers = \Config::get('custom.show_legsectt');




        $query = User::query(); 
      

        $query->when($nolegsecttusers == false, function ($q) {
          return $q->NotSimpleAndHiddenUsers();
       
        });

        $users = $query->orderBy('updated_at', 'desc')->get();

        return view('admin.users.index', compact('users', 'nodisplnameusers', 'conflictdesignempl'));
    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('user_create')) {
            return abort(401);
        }
        
        $roles = \App\Role::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

       
        return view('admin.users.create', compact('roles'));
    }

    


    /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUsersRequest $request)
    {
        if (! Gate::allows('user_create')) {
            return abort(401);
        }
        $user = User::create($request->all());

        $route = null;

        if( strpos($request['username'],'de.') === 0 ){
            

            $route = new \App\Routing(
                [
                 'route' => substr($request['username'],3),
                 'last_forwarded_to' => substr($request['username'],3)
                ]);

        }
        else{
            $route = new \App\Routing(['route' => '',
                                  'last_forwarded_to' => ''
                                  ]);
        }

        $user->routing()->save($route);

        return redirect()->route('admin.users.index');
    }
    public function create_dataentry($id)
    {
        if (! Gate::allows('user_create')) {
            return abort(401);
        }
        
        $user = User::findOrFail($id);
             

        $userdataentry = User::whereusername('de.'.$user->username)->first();

        if( !$userdataentry ){

            $userdataentry = User::create(
                [
                    'name' => $user->Title, 
                    'email' => 'admin@admin.com', 
                    'username' => 'de.'.$user->username, 
                    'displayname' => 'Asst',
                    'role_id' => 2,
                    'password' => 'password'
                ]
            );
                        

            $userdataentry->routing()->create(
                [
                 'route' => $user->username,
                 'last_forwarded_to' => $user->username
                ]
            );

          \Session::flash('message-success', 'de.'.$user->username .' created');

        }
        else
        {
          \Session::flash('message-danger', 'de.'.$user->username. ' - already exists');
        }
       

        return redirect()->route('admin.users.index');

    }
    


    /**
     * Show the form for editing User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('user_edit')) {
            return abort(401);
        }
        
        $roles = \App\Role::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function editsimple($id)
    {
        if (! Gate::allows('user_edit')) {
            return abort(401);
        }
        
        $roles = \App\Role::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        $user = User::findOrFail($id);
       
        return view('admin.users.editsimple', compact('user', 'roles'));
    }

    /**
     * Update User in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUsersRequest $request, $id)
    {
        if (! Gate::allows('user_edit')) {
            return abort(401);
        }
        $user = User::findOrFail($id);
        $user->update($request->all());

        //if this is JS or AS or SS, update their de. title too

        if($user->isJSorASorSSLevel()){

            $userdataentry = User::whereusername('de.'.$user->username)->first();

            if( $userdataentry ){
                $userdataentry->update( [
                    'name' => $user->Title,
                ]
                );
            }

        }



        return redirect()->route('admin.users.index');
    }

    function random_str($length, $keyspace = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ')
    {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
     public function password_reset($id)
    {
        if (! Gate::allows('user_create')) {
            return abort(401);
        }
        
        $user = User::findOrFail($id);
        $password = 'pass123';
        if(false !== strpos($user->username, 'us.')){
          $password = 'pass456'; //us.
        } else if(false === strpos($user->username, 'sn.')){
          $password = 'pass789'; //ds., js, as,....
        }


        $sendemail = false;

        if(\Config::get('custom.vps')) //email available?
        {
          if('admin@admin.com' != $user->email)
          {
             $password = $this->random_str(6);
             $sendemail = true;
          }
        }
        //password set when the User model is saved for the first time. Then later on you can update/change the password as needed. 
        //there is a mutator method to set the password

        $user->update( [
           'password' => /*Hash::make*/($password),
        ]);

        if($sendemail){
          $body = 'Your password has been reset:<br><br>User Id: <b>' . $user->username . '</b><br>Password: <b>' . $password . '</b><br><br><br>Regards<br>Admin';

          try{

            \Mail::send(['text'=>'email'], ['name'=>$user->name], function ($m) use ($body, $user){
                 $m->from('harilegisec@gmail.com', 'OT');
                 $m->subject('[Overtime Allowance App] Password Reset');

                 $m->to($user->email, $user->name)->setBody($body, 'text/html');
             });
            }
          catch(\Exception $e){
              
          }
        }
             
       
        \Session::flash('message-success', $user->username .'\'s password set to ' .  $password .  ($sendemail ? (' and emailed to ' . $user->email) : ''));
       

        return redirect()->route('admin.users.index');

    }


    /**
     * Display User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('user_view')) {
            return abort(401);
        }
        
        $roles = \App\Role::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');$routings = \App\Routing::where('user_id', $id)->get();

        $user = User::findOrFail($id);

        return view('admin.users.show', compact('user', 'routings'));
    }


    /**
     * Remove User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('user_delete')) {
            return abort(401);
        }
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index');
    }

    /**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('user_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = User::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    public function clearold()
    {        
        if(!\Auth::user()->isAdmin()){
            return abort(401);
        }

       
        $merged = \App\Form::distinct()->get(['owner'])->pluck('owner');
        $merged2 = \App\Form::distinct()->get(['creator'])->pluck('creator');
        $merged = $merged->merge($merged2)->unique();
        $merged2 = collect();
        $merged3 = \App\Form::distinct()->get(['submitted_by'])->pluck('submitted_by');

        foreach ($merged3 as $key => $value) {
           $arr = explode(",", $value);

           $merged2 = $merged2->merge($arr)->unique();
           
        }

        $merged = $merged->merge($merged2)->unique();
       



              
       
        {
          $emp = \App\User::wherenotin('username', $merged)
                ->whereroleId(2)
                //->whereDate('created_at', '<', $date_ago->toDateString())
                //->whereDate('updated_at', '<', $date_ago->toDateString())
                ->orderby('id','desc')->pluck('name','username');

          dd($emp);
            
        } 

    }

}
