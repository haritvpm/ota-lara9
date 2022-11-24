<?php

namespace App\Http\Controllers\Admin;

use App\Form;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreFormsRequest;
use App\Http\Requests\Admin\UpdateFormsRequest;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;


class FormsController extends Controller
{
    /**
     * Display a listing of Form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('form_access')) {
            return abort(401);
        }

         

        
        if (request()->ajax()) {
            $query = Form::orderby('id','desc');
           
            $template = 'actionsTemplate';
            
            $table = Datatables::of($query);

            $table->setRowAttr([
                'data-entry-id' => '{{$id}}',
            ]);

            $table->addColumn('massDelete', '&nbsp;');
            $table->addColumn('actions', '&nbsp;')->rawColumns(['actions']);;
            $table->editColumn('actions', function ($row) use ($template) {
                $gateKey  = 'form_';
                $routeKey = 'admin.forms';

                return view($template, compact('row', 'gateKey', 'routeKey'));
            });
            $table->editColumn('owner', function ($row) {
                return $row->owner ? $row->owner : '';
            });
           
            $table->editColumn('form_no', function ($row) {
                return $row->form_no ? $row->form_no : '';
            });

            $table->editColumn('duty_date', function ($row) {
                return $row->duty_date ? $row->duty_date : '';
            });
            $table->editColumn('date_from', function ($row) {
                return $row->date_from ? $row->date_from : '';
            });
            $table->editColumn('date_to', function ($row) {
                return $row->date_to ? $row->date_to : '';
            });

            return $table->make(true);
        }

        $date_ago = Carbon::today()->subYears(3);

        $session_todelete_array = \App\Session::
                                whereDate('created_at', '<', $date_ago->toDateString())
                                ->whereDate('updated_at', '<', $date_ago->toDateString())
                                ->pluck('name');


        return view('admin.forms.index', compact('session_todelete_array', 'date_ago'));
    }


    public function clearoldforms()
    {
        $str_session = null; 
        $session = $request->query('session_todelete');

        if(!\Auth::user()->isAdmin()){
            return abort(401);
        }

        $date_ago = Carbon::today()->subYears(3);
        $forms = Form::whereSession($session)
                      ->whereDate('created_at', '<', $date_ago->toDateString())
                      ->whereDate('updated_at', '<', $date_ago->toDateString())
                      ->orderby('id','desc')->get();

        $dump = $request->query('delbtn') != 'del';
        
        if($dump){
            if($forms->count()){
                dd($forms->pluck('id'));
            } else {
                echo 'No forms found';   
            }
        } else {
            
            $count = 0;

            if($forms->count())
            {
                foreach ($forms as $form) {
                    $form->delete();
                    $count++;
                }
                
                echo 'deleted ' . $count . ' forms';
            }
            else {
                echo 'No forms to delete';   
            }

            $overtimes = \App\Overtime::with('forms')
                             ->wherehas( 'form', function($q) use ($session,$date_ago){
                               $q->where('session',$session)
                               ->whereDate('created_at', '<', $date_ago->toDateString())
                               ->whereDate('updated_at', '<', $date_ago->toDateString());
                         })->count();  

            if($overtimes){
                dd('Error: Found ' . $overtimes . ' ot rows that belong to session '. $session );
            }



        }

    }
    /**
     * Show the form for creating new Form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*if (! Gate::allows('form_create')) {
            return abort(401);
        }        $enum_overtime_slot = Form::$enum_overtime_slot;
            
        return view('admin.forms.create', compact('enum_overtime_slot'));*/
    }

    /**
     * Store a newly created Form in storage.
     *
     * @param  \App\Http\Requests\StoreFormsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFormsRequest $request)
    {
        /*if (! Gate::allows('form_create')) {
            return abort(401);
        }
        $form = Form::create($request->all());



        return redirect()->route('admin.forms.index');*/
    }


    /**
     * Show the form for editing Form.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('form_edit')) {
            return abort(401);
        }        $enum_overtime_slot = Form::$enum_overtime_slot;
            
        $form = Form::findOrFail($id);

        return view('admin.forms.edit', compact('form', 'enum_overtime_slot'));
    }

    /**
     * Update Form in storage.
     *
     * @param  \App\Http\Requests\UpdateFormsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFormsRequest $request, $id)
    {
        if (! Gate::allows('form_edit')) {
            return abort(401);
        }
        $form = Form::findOrFail($id);
        $form->update($request->all());



        return redirect()->route('admin.forms.index');
    }


    /**
     * Display Form.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('form_view')) {
            return abort(401);
        }
        $overtimes = \App\Overtime::where('form_id', $id)->get();

        $form = Form::findOrFail($id);

        return view('admin.forms.show', compact('form', 'overtimes'));
    }


    /**
     * Remove Form from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('form_delete')) {
            return abort(401);
        }
        $form = Form::findOrFail($id);
        $form->delete();

        return redirect()->route('admin.forms.index');
    }

    /**
     * Delete all selected Form at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('form_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Form::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}
