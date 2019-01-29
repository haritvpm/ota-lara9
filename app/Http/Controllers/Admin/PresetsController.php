<?php

namespace App\Http\Controllers\Admin;

use App\Preset;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


class PresetsController extends Controller
{
    /**
     * Display a listing of Preset.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $presets = null;

        if(\Auth::user()->isAdmin()){

            $nolegsecttusers = env('SHOW_LEGSECTT', 'TRUE');

            $usersoflegsectt = null;

            if(!$nolegsecttusers){
                $usersoflegsectt  = \App\User::SimpleOrHiddenUsers()
                ->pluck('id');
            }

            $query = Preset::query();
   

            $query->when($nolegsecttusers == false, function ($q) use ($usersoflegsectt){
              return $q->whereNotIn('user_id', $usersoflegsectt);
                     
            });



            $presets = $query->orderby('updated_at', 'asc')->get(); //this helps to delete older entries which comes first on delete refresh
        }
        else{
            $presets = Preset::where('user_id',\Auth::user()->id)->orderby('updated_at', 'desc')->get();
        }

     

        return view('admin.presets.index', compact('presets'));
    }

    public function ajaxfind($name, $id = null)
    {
       $temp = null;

       if($id != null){
         //loaded from within this controller
          $temp = Preset::findOrFail($id)->pens;
       }
       else{
        //loaded from program
          $temp = Preset::where('name', $name)
                     ->where('user_id', \Auth::user()->id)
                     ->first()->pens;
        
       }

      
      $arr = explode(",", $temp);

      //when we passed in a pen of 'E11956', it was shown as error, probably because it was interpreted as a number.
      //so enclose every pen in quotes.
      
      $placeholder = implode(', ', array_fill(0, count($arr), '?')); //this returns like  '?, ?'
      
      $empl = \App\Employee::with('designation')
                    ->wherein('pen', $arr )
                    ->orderByRaw("FIELD(pen, $placeholder)", $arr )
                   
                    ->get();
      

      $combined = $empl->mapWithKeys(function ($emp) {
           return   [ ($emp->pen . '-' . $emp->name) => $emp->designation['designation']]; 
      });
      
      return $combined;
        
    }

    /**
     * Show the form for creating new Preset.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        $users = \App\User::get()->pluck('username', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        return view('admin.presets.create', compact('users'));
    }

    /**
     * Store a newly created Preset in storage.
     *
     * @param  \App\Http\Requests\StorePresetsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
                      
        $presetname = str_replace(["\\", "/"], '-',$request['name']);

        $user_id = \Auth::user()->isAdmin() ? $request['user_id'] : \Auth::user()->id;

        if(Preset::where('name',$presetname )
             ->where('user_id', $user_id)
             ->exists())
        {
            //if(Request::ajax())
            {
              return response()->json([
               'result' => false,
               'error' => $presetname . '- already exists'
                 ]);
            } /*else {

              return redirect()->back()->withErrors(['error: ' . $presetname . '- already exists' ])->withInput(Input::all());;
            }*/
        }


        $pens = collect($request['pens'])
                ->transform(function($pen_name)
        {
            $tmp = explode("-",$pen_name);
            return $tmp[0];
        });

        $string = $pens->implode(',');
       
       

        $preset = Preset::create([
            'pens' => $string,
            'name' => $presetname,
            'user_id' => $user_id,
        ]);

       //as presets are created by ajax, we have to return ajax
       //if($request->ajax())
       {

          return response()->json([
             'result' => true,
             
          ]);
      }
       
      return redirect()->route('admin.presets.index');
    }


    /**
     * Show the form for editing Preset.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $users = \App\User::get()->pluck('username', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        $preset = Preset::findOrFail($id);


        return view('admin.presets.edit', compact('preset', 'users'));
    }

    /**
     * Update Preset in storage.
     *
     * @param  \App\Http\Requests\UpdatePresetsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
       $preset = Preset::findOrFail($id);

       $presetname = str_replace(["\\", "/"], '-',$request['name']);

       if(Preset::where('name', $presetname)
                    ->where('user_id',$preset->user_id)
                    ->where('id','<>', $id)->exists())
       {
           /* return response()->json([
               'result' => false,
               'error' => $presetname . '- already exists'
                 ]);*/
          
            return redirect()->back()->withErrors(['error: ' . $presetname . '- already exists' ])->withInput(Input::all());;

       }


       $pens = $request['pens'];

       //if these are PEN, we need to remove white space
       if(strncasecmp($presetname, 'default_', 8) != 0){
         $pens = str_replace(["\r\n", "\r", "\n"], '', $pens);
         $pens = str_replace(' ', '', $pens);
       }


       $user_id = \Auth::user()->isAdmin() ? $request['user_id'] : \Auth::user()->id;

       $preset->update([
         'pens' => $pens,
         'name' => $presetname,
         'user_id' => $user_id,
        ]);


       //return redirect()->route('admin.presets.index');
       return redirect()->route('admin.presets.show', ['id' => $id]);
    }


    /**
     * Display Preset.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $preset = Preset::findOrFail($id);

        $loadedpreset = $this->ajaxfind($preset->name, $id );
       
        return view('admin.presets.show', compact('preset', 'loadedpreset'));
    }


    /**
     * Remove Preset from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $preset = Preset::findOrFail($id);
        $preset->delete();

        return redirect()->route('admin.presets.index');
    }

    /**
     * Delete all selected Preset at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        
        if ($request->input('ids')) {
            $entries = Preset::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}
