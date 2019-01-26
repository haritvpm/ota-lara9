<?php

namespace App\Http\Controllers;

//use Mail;
use App\Form;
use App\FormOther;
use App\Http\Requests;
use Illuminate\Http\Request;
use Response;
use JavaScript;
use App\Exemptionform;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       /* Mail::send('email', ['user' => 'sdhfksdjf'], function ($m)  {
            $m->from('harirs@gmail.com', 'Your Application');

            $m->to('harirs@gmail.com', 'Hari')->subject('Your Reminder!');
        });*/

        if(\Auth::check()){
            if(\Auth::user()->isHidden() && !auth()->user()->isAdmin()) { 
                \Auth::logout();
                
                return redirect('login')->withErrors( 'Your account has been disabled. Contact admin.');
            }
        }


        //verify if we have entered data correctly
        if( auth()->user()->isAdmin() )
        {
            /*$users_with_no_route  = array();
            $users = \App\User::with("routing")
                            ->where('role_id','2')
                            ->get()->except('admin');*/

            $users_with_no_route  = \App\User::whereDoesntHave("routing")->where('role_id','2')
                            ->pluck('username')->except('admin');
/*
            foreach ($users as $user) {
                if($user->routing == null){

                    array_push($users_with_no_route , $user->username);
                }
            }*/
            
            if(count($users_with_no_route)){
                \Session::flash('message-danger', 
                            'Users with no route: ' . $users_with_no_route->implode(',') );
            }
/*
            $routes_with_no_user = array();
            $routes = \App\Routing::with("user")->get();
            foreach ($routes as $route) {
              if($route->user == null){
                    array_push($routes_with_no_user , $route->id);
                }
            }*/

            $routes_with_no_user  = \App\Routing::whereDoesntHave("user")
                            ->pluck('id');

            if(count($routes_with_no_user)){
                \Session::flash('message-info', 
                            'Orphaned routes: ' . $routes_with_no_user->implode(',') );
            }



        }


        $forms = null;
        $formsother = null;
        $forms_ex = null;

        $session_array = array();
        $sessions = null;

        if( auth()->user()->isAdmin() || auth()->user()->isServices())
        {            
            $sessions  = \App\Session::whereshowInDatatable('Yes')
                                            ->Orwhere('dataentry_allowed','Yes')
                                            ->get();

            $session_array = $sessions->pluck('name')->toarray();

            $forms = Form::with(['created_by','owned_by'])
                     ->whereIn('session',$session_array);

            $formsother = FormOther::with(['created_by','owned_by'])
                     ->whereIn('session',$session_array);


           // $sessionforexemption = \App\Session::whereExemptionEntry('Yes')->latest()->first();
           // $forms_ex = Exemptionform::with(['created_by','owned_by'])
            //         ->where('session',$sessionforexemption->name);
                                
                        
        }
        else
        {
            //user can login and see forms even after data entry is disabled

            $sessions  = \App\Session::whereshowInDatatable('Yes')
                                            ->Orwhere('dataentry_allowed','Yes')
                                            ->get();

            $session_array = $sessions->pluck('name')->toarray();

            if(!auth()->user()->isOD()){
                $forms = Form::with(['created_by','owned_by'])
                         //->whereSession($session)
                         ->whereIn('session',$session_array)
                         ->CreatedOrOwnedOrApprovedByLoggedInUser();

                /*         
                if(\Schema::hasTable('exemptions')){
                    $sessionforexemption = \App\Session::whereExemptionEntry('Yes')->latest()->first();
                    $forms_ex = Exemptionform::with(['created_by','owned_by'])
                         ->where('session',$sessionforexemption->name)
                         ->CreatedOrOwnedOrApprovedByLoggedInUser();
                 }*/


            }
            else{
                $formsother = FormOther::with(['created_by','owned_by'])
                         //->whereSession($session)
                         ->whereIn('session',$session_array)
                         ->CreatedOrOwnedOrApprovedByLoggedInUser();   
            }
        }

        /*
        $total = $forms->get()->count();
        $draft = $forms->filterStatus('Draft')->count();
        $to_approve = $forms->filterStatus('To_approve')->count();
        $pending = $forms->filterStatus('Pending')->count();
        $submitted = $forms->filterStatus('Submitted')->count();
        */

        if( env('SHOW_LEGSECTT', true)){
            if($forms){
                $forms = $forms->get();
            }
        }


        $total = 0;
        $submitted = 0;
        $draft = 0;
        $to_approve = 0;
        $pending = 0;

        $info = array();
        $info_submitted = array(); //not much use
        $users_submitted_or_created = array('admin','audit'); //remove admin from users_not_submitted_yet

        if($forms){
            foreach( $forms as $form ){
                $total += 1;
                $keysession = $form->session;

                if(!array_key_exists($keysession, $info)){
                    $info[$keysession] = [];
                }


                /*if(!array_key_exists($keysession, $info_submitted)){
                    $info_submitted[$keysession] = [];
                }*/


                $key           = $form->owner == 'admin' ? 'submitted' : 'created';
                $key_submitted = $form->owner == 'admin' ? 'submitted' : 'created';


                //if form creator is dataentry, consider section
                $formcreator = $form->owner;
                if(strpos($formcreator,'de.')===0){
                    $formcreator = substr($formcreator,3);
                }

                array_push($users_submitted_or_created, $formcreator);
                            

                //user might have been deleted
                //also we need used id if JS or above
                $key2 = optional($form->owned_by)->DispNameWithNameShort ;
                if($key2 == null || strlen($key2) <=2 ){
                    $key2 .='(' .$form->owner . ')';
                }

                //user might have been deleted
                //also we need used id if JS or above
                $key3 = optional($form->created_by)->Title ;
                if($key3 == null || strlen($key3) <=2 ){
                    $key3 .='(' .$form->creator . ')';
                }


                if(!array_key_exists($key2, $info[$keysession])){

                    $info[$keysession][ $key2 ]['submitted'] = 0;
                    $info[$keysession][ $key2 ]['created'] = 0;

                    $info[$keysession][ $key2 ][$key] = 1;
                }
                else
                    $info[$keysession][ $key2 ][$key] += 1;



               /* if(!array_key_exists($key3, $info_submitted[$keysession])){

                    $info_submitted[$keysession][ $key3 ]['submitted'] = 0;
                    $info_submitted[$keysession][ $key3 ]['created'] = 0;

                    $info_submitted[$keysession][ $key3 ][$key_submitted] = 1;
                }
                else
                    $info_submitted[$keysession][ $key3 ][$key_submitted] += 1;*/
           

                if($form->owner == 'admin') {$submitted += 1;}
                else if($form->owner == $form->creator) {$draft += 1;}
                else if($form->owner != $form->creator && $form->owner != auth()->user()->username) {$pending += 1;}
                else if($form->owner != $form->creator && $form->owner == auth()->user()->username) {$to_approve += 1;}
                else{
                    dd($form);
                }
                
            }
        }

        /*foreach ($info_submitted as $key => $value) {
            uasort($info_submitted[$key], function($a, $b){
                if($a['submitted'] == $b['submitted']) {
                    return 0;
                }
                return ($a['submitted'] > $b['submitted']) ? -1 : 1;
            } );
        }*/
        
        foreach ($info as $key => $value) {
                        
            uasort($info[$key], function($a, $b){
                if($a['created'] == $b['created']) {
                    return 0;
                }
                return ($a['created'] > $b['created']) ? -1 : 1;
            } );
        }

        //other dept

        $total_other = 0;
        $submitted_other = 0;
        $draft_other = 0;
        $to_approve_other = 0;
        $pending_other = 0;
        $info_other = array();
     
        if($formsother){
            $formsother = $formsother->get();
        }

        if($formsother){
            foreach( $formsother as $form ){
                $total_other += 1;

                $key = $form->owner == 'admin' ? 'submitted' : 'created';

                array_push($users_submitted_or_created, $form->creator);
              
                if(!array_key_exists($form->created_by->Title, $info)){

                    $info_other[ $form->created_by->Title ]['submitted'] = 0;
                    $info_other[ $form->created_by->Title ]['created'] = 0;

                    $info_other[ $form->created_by->Title ][$key] = 1;
                }
                else
                    $info_other[ $form->created_by->Title ][$key] += 1;
           

                if($form->owner == 'admin') {$submitted_other += 1;}
                else if($form->owner == $form->creator) {$draft_other += 1;}
                else if($form->owner != $form->creator && $form->owner != auth()->user()->username) {$pending_other += 1;}
                else if($form->owner != $form->creator && $form->owner == auth()->user()->username) {$to_approve_other += 1;}
                else{
                    dd($form);
                }
                
            }
        }

        //exemption forms


        if($forms_ex){
            $forms_ex = $forms_ex->get();
        }

        $total_ex = 0;
        $submitted_ex = 0;
        $draft_ex = 0;
        $to_approve_ex = 0;
        $pending_ex = 0;

        $info_ex = array();
        
        /*     
        if($forms_ex){
            foreach( $forms_ex as $form ){
                $total_ex += 1;
                $keysession = $form->session;

                if(!array_key_exists($keysession, $info_ex)){
                    $info_ex[$keysession] = [];
                }

                $key = $form->owner == 'admin' ? 'submitted' : 'created';
                //if form creator is dataentry, consider section
                $formcreator = $form->owner;
                if(strpos($formcreator,'de.')===0){
                    $formcreator = substr($formcreator,3);
                }

                //user might have been deleted
                //also we need used id if JS or above
                $key2 = optional($form->owned_by)->Title ;
                if($key2 == null || strlen($key2) <=2 ){
                    $key2 .='(' .$form->owner . ')';
                }
                
                if(!array_key_exists($key2, $info_ex[$keysession])){

                    $info_ex[$keysession][ $key2 ]['submitted'] = 0;
                    $info_ex[$keysession][ $key2 ]['created'] = 0;

                    $info_ex[$keysession][ $key2 ][$key] = 1;
                }
                else
                    $info_ex[$keysession][ $key2 ][$key] += 1;

       

                if($form->owner == 'admin') {$submitted_ex += 1;}
                else if($form->owner == $form->creator) {$draft_ex += 1;}
                else if($form->owner != $form->creator && $form->owner != auth()->user()->username) {$pending_ex += 1;}
                else if($form->owner != $form->creator && $form->owner == auth()->user()->username) {$to_approve_ex += 1;}
                else{
                    dd($form);
                }
                
            }
        }
        
        foreach ($info_ex as $key => $value) {
                        
            uasort($info_ex[$key], function($a, $b){
                if($a['created'] == $b['created']) {
                    return 0;
                }
                return ($a['created'] > $b['created']) ? -1 : 1;
            } );
        }
*/

        ///////////////////////////////////////


       //this includes all staff
       //since display name can be null sometimes, plucking by displayname as key will remove items
        $users_not_submitted_yet = array();;
        /*
        $users_not_submitted_yet = \App\User::whereNotIn( 'username',$users_submitted_or_created )
        ->where('username','not like', 'de.%')
        ->pluck('displayname','name');*/
 
        //sections and admins have nothing to approve
        if ( auth()->user()->isDataEntryLevel() || auth()->user()->isAdminorAudit() ||
             auth()->user()->isServices() ) {
           // It starts with 'http'
            $to_approve = -1;
            $to_approve_other = -1;
            $to_approve_ex = -1;
        }

        $pending_approval = 0;
        if ( auth()->user()->isFinalLevel() ) {
           // It starts with 'http'
            $pending_approval = -1;
        }
    
        $formcount = array();
        $formlastsubmitteddate = array();
        $formlastsubmittedby = array();
        $formcountother = array();
        $formotherlastsubmitteddate = array();
        $formotherlastsubmittedby = array();
        $last_form_no = array();

        if( auth()->user()->isAdmin() ){
            foreach ($sessions as $session) {
               
                if( env('SHOW_LEGSECTT', true)){
                    $forms = \App\Form::with('created_by') 
                                        ->whereSession( $session->name )
                                        ->where('owner','admin')
                                        ->orderby('submitted_on', 'desc')->get();
                   

                    $formcount[$session->name] = $forms->count();
                    if($forms->count()){

                        $lastform =  \App\Form::find($forms->pluck('id')->first());
                        $last_form_no[$session->name] = $forms->max('form_no');

                        $formlastsubmitteddate[$session->name] = $lastform->submitted_on;
                        $formlastsubmittedby[$session->name] =   $lastform->created_by->Title;
                    }
                }

                //other dept

                $formsother = \App\FormOther::with('created_by') 
                                    ->whereSession( $session->name )
                                    ->whereOwner('admin')
                                    ->orderby('submitted_on', 'desc')->get();
               

                $formcountother[$session->name] = $formsother->count();
                if($formsother->count()){

                    $lastform =  \App\FormOther::find($formsother->pluck('id')->first());
                    
                    $formotherlastsubmitteddate[$session->name] = $lastform->submitted_on;
                    $formotherlastsubmittedby[$session->name] =   $lastform->created_by->name;
                }


            }
        }

       
       $marqueetext = \App\Setting::where('name','scrolltext')->pluck('value')->first();


       $welcometext = '';

       $displaynameempty = false;
       
        if( !auth()->user()->isAdminorAudit() && 
            !auth()->user()->isDataEntryLevel() &&
            !auth()->user()->isOD() 
            ){

            if(auth()->user()->displayname != ''){
                $welcometext = '<br><h4>Please make sure your name is displayed at the top right corner.</h4>';

            } else {
                $welcometext = '<br><h4>Please enter your name</h4> To enter name, click your user-id at the top right corner of this page and then select Profile.';
                $displaynameempty = true;
            }

            if(auth()->user()->isJSorASorSSLevel()){
                $welcometext .= '<br> If your title has changed from ' .auth()->user()->Title . ', please contact Accounts D'; 
            }

        }

    $showloggedinmessage = false;
    $displayname = auth()->user()->displayname;
    $isJSorASorSSLevel = auth()->user()->isJSorASorSSLevel();
    $title = auth()->user()->TitleFull;

    if(!$displaynameempty){
        $coma = strpos($displayname,',');
        if($coma !== false){
            $displayname = substr($displayname,0, $coma);
        }
    }

    if(!auth()->user()->isDataEntryLevel() && !auth()->user()->isAdminorAudit() && !auth()->user()->isServices()){
        if(!session()->get('loggedinmessageshown',false)){
            session()->put('loggedinmessageshown', true);
            $showloggedinmessage = true;

        }
    }
    
    JavaScript::put([
            'showloggedinmessage' => $showloggedinmessage,
            'displayname' => $displayname,
            'displaynameempty' => $displaynameempty,
            'isJSorASorSSLevel' => $isJSorASorSSLevel,
            'title' => $title,
    ]);

////////////////
    $amount_all = 0;
    $amount_approved = 0;
    $amount_all_sectt = 0;
    $amount_approved_sectt = 0;
    $session_latest = null;
    
    if( auth()->user()->isAdmin() && env('SHOW_LEGSECTT', true)){
        
        $session_latest =  \App\Session::latest()->first()->name;
        /*
        $amount = \App\Overtime::with('forms')->whereHas('form', function($query) use  ($session_latest) {
                      $query->where('session', $session_latest);
                    });
                    ->sum(function($t){ 
                            return $t->count * $t->rate; 
                        });*/

        $amount_approved_sectt = \DB::table('overtimes')
        ->join('forms', 'forms.id', '=', 'overtimes.form_id')
        ->where('forms.session', $session_latest)
        ->where('forms.owner', 'admin')
        ->where('forms.creator', '<>', 'sn.amhostel')
        //->where('forms.creator', 'not like', '%sn.ma%') section officers are ours
        ->select(\DB::raw('sum(overtimes.count*overtimes.rate) AS total_amount'))
        ->first()->total_amount;

        $amount_all_sectt  = \DB::table('overtimes')
        ->join('forms', 'forms.id', '=', 'overtimes.form_id')
        ->where('forms.session', $session_latest)
        ->where('forms.creator', '<>', 'sn.amhostel')
       
        ->select(\DB::raw('sum(overtimes.count*overtimes.rate) AS total_amount'))
        ->first()->total_amount;

         $amount_approved= \DB::table('overtimes')
        ->join('forms', 'forms.id', '=', 'overtimes.form_id')
        ->where('forms.session', $session_latest)
        ->where('forms.owner', 'admin')

        //->where('forms.creator', 'not like', '%sn.ma%') section officers are ours
        ->select(\DB::raw('sum(overtimes.count*overtimes.rate) AS total_amount'))
        ->first()->total_amount;

        $amount_all  = \DB::table('overtimes')
        ->join('forms', 'forms.id', '=', 'overtimes.form_id')
        ->where('forms.session', $session_latest)
        ->select(\DB::raw('sum(overtimes.count*overtimes.rate) AS total_amount'))
        ->first()->total_amount;
              
        setlocale(LC_MONETARY, 'en_in');

        $amount_all = money_format('%.0n', (double)$amount_all);
        $amount_approved = money_format('%.0n', (double)$amount_approved);
        $amount_all_sectt = money_format('%.0n', (double)$amount_all_sectt);
        $amount_approved_sectt = money_format('%.0n', (double)$amount_approved_sectt);
    }

    /////////////



    return view('home', compact('session_array', 'users_not_submitted_yet',
                                   'info', 'info_submitted', 'total','draft', 'to_approve','pending','submitted', //staff
                                    'info_other','total_other','draft_other', 'to_approve_other','pending_other','submitted_other', //od
                                     'info_ex', 'total_ex','draft_ex', 'to_approve_ex','pending_ex','submitted_ex', //exemption forms
                                    'formcount', 'formlastsubmitteddate', 'formlastsubmittedby', 
                                    'formcountother', 'formotherlastsubmitteddate', 'formotherlastsubmittedby', 'last_form_no',
                                    'marqueetext','pending_approval',
                                    'welcometext', 'amount_all_sectt', 'amount_approved_sectt', 
                                    'amount_all', 'amount_approved','session_latest'

                                     ));
    }

    public function goview($file)
    {
        $filename = 'app/public/' . $file;
        $path = storage_path($filename);

        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
        ]);
    }
}
