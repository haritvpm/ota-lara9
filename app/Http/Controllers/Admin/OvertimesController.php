<?php

namespace App\Http\Controllers\Admin;

use App\Overtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOvertimesRequest;
use App\Http\Requests\Admin\UpdateOvertimesRequest;
use Yajra\DataTables\DataTables;

class OvertimesController extends Controller
{
    /**
     * Display a listing of Overtime.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       /*  if (! Gate::allows('overtime_access')) {
            return abort(401);
        }
 */

        
        if (request()->ajax()) {
            $query = Overtime::query();
            $query->with("form");
            $template = 'actionsTemplate';
            
            $table = Datatables::of($query);

            $table->setRowAttr([
                'data-entry-id' => '{{$id}}',
            ]);
            $table->addColumn('massDelete', '&nbsp;');
            $table->addColumn('actions', '&nbsp;')->rawColumns(['actions']);;
            $table->editColumn('actions', function ($row) use ($template) {
                $gateKey  = 'overtime_';
                $routeKey = 'admin.overtimes';

                return view($template, compact('row', 'gateKey', 'routeKey'));
            });
            $table->editColumn('worknature', function ($row) {
                return $row->worknature ? $row->worknature : '';
            });

            return $table->make(true);
        }

        return view('admin.overtimes.index');
    }

    /**
     * Show the form for creating new Overtime.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /* if (! Gate::allows('overtime_create')) {
            return abort(401);
        } */
        
        $forms = \App\Form::get()->pluck('session', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        return view('admin.overtimes.create', compact('forms'));
    }

    /**
     * Store a newly created Overtime in storage.
     *
     * @param  \App\Http\Requests\StoreOvertimesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOvertimesRequest $request)
    {
        /* if (! Gate::allows('overtime_create')) {
            return abort(401);
        } */
        $overtime = Overtime::create($request->all());



        return redirect()->route('admin.overtimes.index');
    }


    /**
     * Show the form for editing Overtime.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       /*  if (! Gate::allows('overtime_edit')) {
            return abort(401);
        } */
        
        $forms = \App\Form::get()->pluck('session', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        $overtime = Overtime::findOrFail($id);

        return view('admin.overtimes.edit', compact('overtime', 'forms'));
    }

    /**
     * Update Overtime in storage.
     *
     * @param  \App\Http\Requests\UpdateOvertimesRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOvertimesRequest $request, $id)
    {
       /*  if (! Gate::allows('overtime_edit')) {
            return abort(401);
        } */
        $overtime = Overtime::findOrFail($id);
        $overtime->update($request->all());



        return redirect()->route('admin.overtimes.index');
    }


    /**
     * Display Overtime.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       /*  if (! Gate::allows('overtime_view')) {
            return abort(401);
        } */
        $overtime = Overtime::findOrFail($id);

        return view('admin.overtimes.show', compact('overtime'));
    }


    /**
     * Remove Overtime from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /* if (! Gate::allows('overtime_delete')) {
            return abort(401);
        } */
        $overtime = Overtime::findOrFail($id);
        $overtime->delete();

        return redirect()->route('admin.overtimes.index');
    }

    /**
     * Delete all selected Overtime at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('overtime_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Overtime::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

    public function generateRandomString($length = 10) {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    //we've already fixed PEN field by splitting it to PEN and NAME. so this is not needed
    public function fixx(Request $request)
    {


        $c = 0;
        //chunk was missing half the items. so we had to run in repeatedly until all is done
        
        /*
        /*
        Overtime::whereRaw("POSITION( '-' in `pen`) <> 0")
                ->wherenull('name')
        ->chunk(200, function ($items) use(&$c) {
          foreach ($items as $item) {
            $tmp = strpos($item['pen'], '-');

            $name = trim(substr($item['pen'],$tmp+1),"- ");
            $pen = trim(substr($item['pen'],0,$tmp),"- ");
            $c++;
            // might be more logic here
            $item->update(['pen' => $pen, 'name' => $name]);
          }
        });*/

/*
        foreach (Overtime::whereRaw("POSITION( '-' in `pen`) <> 0")->wherenull('name')->cursor() as $item) {

            $tmp = strpos($item['pen'], '-');

            if(false !== $tmp){
                $name = trim(substr($item['pen'],$tmp+1),"- ");
                $pen = trim(substr($item['pen'],0,$tmp),"- ");
                //$c++;
                // might be more logic here
                $item->update(['pen' => $pen, 'name' => $name]);
            }
    
        }
        
        

         echo Overtime::wherenull('name')->count() . ' null names<br>';

         $tmp =Overtime::whereRaw("POSITION( '-' in `pen`) <> 0")->get();

echo $tmp->count() . ' items with - in pen';
        dd($tmp);

        

        echo $c . ' updated';
        */


        //for ($n=0; $n < 10; $n++) 
        { 
          /*
            set_time_limit(2000);

        
            for ($i=0; $i < 400000; $i++) { 
                
                Overtime::create(
                    [
                        'pen' => $this->generateRandomString(10),
                        'name' => 'test',
                        'designation' => 'Office Attendant Gr I',
                        'from' => '09:30',
                        'to' => '19:30',
                        'count' => 1,
                        'worknature' => 'test',
                        'form_id' => 1299,

                    ]
                );

            }
            */
        }
    }
}
