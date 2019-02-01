<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\EmployeesOther;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeesOthersRequest;
use App\Http\Requests\Admin\UpdateEmployeesOthersRequest;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Input;

class EmployeesOthersController extends Controller
{
    /**
     * Display a listing of EmployeesOther.
     *
     * @return \Illuminate\Http\Response
     */

     public function index()
    {
        if (! Gate::allows('employees_other_access')) {
            return abort(401);
        }


        $designations_others_todel = null;
        $session_array = null;

         if(\Auth::user()->isAdmin() || 
            \Auth::user()->username == 'od.pol'){

           $session_array = \App\Session::orderby('id','desc')->take(3)->pluck('name')->implode(',');
          
         
           $designations_others_todel = \App\DesignationsOther::with('user')-> where('user_id', \Auth::user()->id )->orderBy('designation', 'asc')->pluck('designation')->prepend('All');
        }


        

        
        if (request()->ajax()) {
            $query = EmployeesOther::latest();
            $query->with("designation");
           // $query->with("added_by");
            if(\Auth::user()->isAdmin())
            {

            }
            else
            {
                $query =  $query->where('added_by',\Auth::user()->username) ;
            }

            $template = 'actionsTemplate';
            
            $table = Datatables::of($query);

            $table->setRowAttr([
                'data-entry-id' => '{{$id}}',
            ]);
            $table->addColumn('massDelete', '&nbsp;');
            $table->addColumn('actions', '&nbsp;')->rawColumns(['actions']);;
            $table->editColumn('actions', function ($row) use ($template) {
                $gateKey  = 'employees_other_';
                $routeKey = 'admin.employees_others';

                return view($template, compact('row', 'gateKey', 'routeKey'));
            });
            $table->editColumn('department_idno', function ($row) {
                return $row->department_idno ? $row->department_idno : '';
            });
            $table->editColumn('ifsc', function ($row) {
                return $row->ifsc ? $row->ifsc : '';
            });

            

            return $table->make(true);
        }

        return view('admin.employees_others.index', compact('designations_others_todel','session_array'));
    }

    /*
    public function index()
    {
        if (! Gate::allows('employees_other_access')) {
            return abort(401);
        }


        $query = EmployeesOther::query();
        $query->with("designation");
       // $query->with("added_by");
        if(\Auth::user()->isAdmin())
        {

        }
        else
        {
            $query =  $query->where('added_by',\Auth::user()->username) ;
        }


        $employees_others = $query->orderby('id','desc')->get();

        return view('admin.employees_others.index', compact('employees_others'));


    }
    */

    public function ajaxfind($search)
    {
       
      /*$temp =  EmployeesOther::where('pen', 'like', '%' . $search . '%')
          ->orWhere('name', 'like', '%' . $search . '%')->orderby('name','asc')->pluck('pen','name')->take(100);

       

       $combined = $temp->map(function ($name, $key) {
            return $name . '-' . $key ;
        });
         
       return $combined->values();
       */

       $temp =  EmployeesOther::with("designation")
        ->Where(function ($query) use ($search) {
                $query->where('pen', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%');
            })
        ->where('added_by', \Auth::user()->username)
        ->orderby('name','asc')->get()->take(100);

       
       $combined = $temp->transform(function ($item) {
            return $item->pen . '-' . $item->name . ', ' . $item->designation->designation  ;
        });
         
       return $combined->toarray();

        
    }
    public function ajaxload($search)
    {
       //search ignored now. load all     

       $temp =  EmployeesOther::with("designation")
          ->orderby('name','asc')->get();
       
       $combined = $temp->transform(function ($item) {
            return $item->pen . '-' . $item->name . ', ' . $item->designation->designation  ;
        });
         
       return $combined->toarray();
        
    }
  
    /**
     * Show the form for creating new EmployeesOther.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('employees_other_create')) {
            return abort(401);
        }
        
        $designations = \App\DesignationsOther::query();
        if(!\Auth::user()->isAdmin()){

            $designations = $designations->where( 'user_id', \Auth::user()->id  );
        }


        $designations = $designations->orderby('designation','asc')->get()->pluck('designation', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        
        $enum_srismt = EmployeesOther::$enum_srismt;
        $enum_account_type = EmployeesOther::$enum_account_type;
            
        return view('admin.employees_others.create', compact('enum_srismt', 'enum_account_type', 'designations'));
    }

    /**
     * Store a newly created EmployeesOther in storage.
     *
     * @param  \App\Http\Requests\StoreEmployeesOthersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeesOthersRequest $request)
    {
        if (! Gate::allows('employees_other_create')) {
            return abort(401);
        }


        $employees_other = new EmployeesOther ($request->all());

        //admins does not save added by
       // if(\Auth::user()->role_id != 1)
        {
            $employees_other['added_by'] = \Auth::user()->username;
        }
        
        $employees_other->save();

        \Session::flash('message-success', 'Success: added: ' . $employees_other->name ); 

        switch($request->submitbutton) {
          
            case 'Save & New':

                return redirect()->route('admin.employees_others.create');
            break;
        }

        return redirect()->route('admin.employees_others.index');
    }


    /**
     * Show the form for editing EmployeesOther.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('employees_other_edit')) {
            return abort(401);
        }
        
               
        $designations = \App\DesignationsOther::query();
        if(!\Auth::user()->isAdmin()){

            $designations = $designations->where( 'user_id', \Auth::user()->id  );
        }


        $designations = $designations->orderby('designation','asc')->get()->pluck('designation', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
      
        $enum_srismt = EmployeesOther::$enum_srismt;
        $enum_account_type = EmployeesOther::$enum_account_type;
            
        $employees_other = EmployeesOther::findOrFail($id);

        if(!\Auth::user()->isAdmin()){
            if( $employees_other->added_by != \Auth::user()->username)
            {
                return abort(401);
            }
        }

        return view('admin.employees_others.edit', compact('employees_other', 'enum_srismt', 'enum_account_type', 'designations'));
    }

    /**
     * Update EmployeesOther in storage.
     *
     * @param  \App\Http\Requests\UpdateEmployeesOthersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeesOthersRequest $request, $id)
    {
        if (! Gate::allows('employees_other_edit')) {
            return abort(401);
        }
        $employees_other = EmployeesOther::findOrFail($id);
        $employees_other->update($request->all());


        return redirect()->route('admin.employees_others.index');
    }


    /**
     * Display EmployeesOther.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('employees_other_view')) {
            return abort(401);
        }
        $employees_other = EmployeesOther::findOrFail($id);

        return view('admin.employees_others.show', compact('employees_other'));
    }


    /**
     * Remove EmployeesOther from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('employees_other_delete')) {
            return abort(401);
        }


        $employees_other = EmployeesOther::findOrFail($id);

        
        if(!\Auth::user()->isAdmin()){
            if( $employees_other->added_by != \Auth::user()->username)
            {
                return abort(401);
            }
        }
        
        $employees_other->delete();

        return redirect()->route('admin.employees_others.index');
    }

    /**
     * Delete all selected EmployeesOther at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('employees_other_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = EmployeesOther::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    public function clearold()
    {
        if (! Gate::allows('employees_other_delete')) {
            return abort(401);
        }
        
        $sessiontonotdelete = explode( ',',Input::get('sessions_toignore'));

        $empl_used = \App\OvertimeOther::with('form')
                ->wherehas( 'form', function($q) use ($sessiontonotdelete){
                          $q->wherenotin('session',$sessiontonotdelete);
                })
                ->distinct()->pluck('designation'); 

        //also prevent empl created in 6 months 

         $dateayearago = Carbon::today()->subMonths(1)->toDateString();


        $emp = EmployeesOther::wherenotin('pen', $empl_used)
                ->whereDate('created_at', '<', $dateayearago)
                ->whereDate('updated_at', '<', $dateayearago);

        
        //\App\DesignationsOther::wherein('id',$emp->pluck('designation_id')->unique())->pluck('designation')->dd();
        //$emp->pluck('designation_id')->unique()->dd();
        $count = $emp->count();

        $emp->delete();


/*
        $dateayearago = Carbon::today()->subYear()->toDateString();
        
        $emp = EmployeesOther::whereDate('created_at', '<', $dateayearago)->whereDate('updated_at', '<', $dateayearago);

        $count = $emp->count();

        $emp->delete();*/



        \Session::flash('message-success', 'Deleted ' . $count . ' employees' ); 


        return redirect()->route('admin.employees_others.index');


    }


}
