<?php

namespace App\Http\Controllers\Admin;

use App\Punching;
use App\Calender;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
//use Yajra\DataTables\DataTables;
//use Laracasts\Utilities\JavaScript\JavaScriptFacade;
use JavaScript;
use Carbon\Carbon;
use Auth;
use Yajra\DataTables\DataTables;
use App\Services\PunchingService;


use Illuminate\Support\Facades\Log;

class PunchingsController extends Controller
{

    //the args are set in route file web.php
    public function ajaxgetpunchsittings($session, $datefrom, $dateto, $pen, $aadhaarid)
    {
        // Log::info($datefrom);

        $dateformatwithoutime = '!' . config('app.date_format'); //! to set time to zero
        $datefrom = Carbon::createFromFormat($dateformatwithoutime, $datefrom)->format('Y-m-d');
        $dateto = Carbon::createFromFormat($dateformatwithoutime, $dateto)->format('Y-m-d');

        // Log::info($session);
        // Calender::where('session_id', $session->id)
        //get all sitting days between these two days
        $sittingsInRange = Calender::with('session')
            ->whereHas('session', function ($query)  use ($session) {
                $query->where('name', $session);
            })
            ->where('date', '>=', $datefrom)
            ->where('date', '<=', $dateto)
            ->where('day_type', 'Sitting day')->get(['date', 'day_type', "punching"]);

        //     Log::info($sittingsInRange);


        $tmp = strpos($pen, '-');
        if (false !== $tmp) {
            $pen = substr($pen, 0, $tmp);
        }
        // Log::info($pen);
        // Log::info($aadhaarid);
        $sittingsWithPunchok = 0;
        $sittingsWithNoPunching = 0;
        $dates = [];
        foreach ($sittingsInRange as $day) {

            $date = Carbon::createFromFormat($dateformatwithoutime, $day->date)->format('Y-m-d');

            $data = [
                'aebasday' => true,
                'date' =>  $day->date,
                'punchin' => "",
                'punchout' => "",
            ];

            //ignore pen if data from aebas and ignore aadhhar if data is from us saving
            $query =  Punching::where('date', $date);
            // $query->when( $day->punching == 'MANUALENTRY' && $pen  && strlen($pen) >= 5, function ($q)  use ($pen) {
            //     return $q->where('pen',$pen);
            // });
            $query->when( /*  $day->punching == 'AEBAS' &&  */$aadhaarid   && strlen($aadhaarid) >= 8, function ($q)  use ($aadhaarid) {
                return $q->where('aadhaarid', $aadhaarid);
            });

            //->wherenotnull('punch_in') //prevent if only one column is available
            //->wherenotnull('punch_out') 

            $temp = $query->first();

            if ($temp) {
                $sittingsWithPunchok++;
                $data['punchin'] =  $temp['punch_in'];
                $data['punchout'] =  $temp['punch_out'];
            }


            if ($day->punching !== 'AEBAS') {

                $sittingsWithNoPunching++;
                $data['aebasday'] =  false; //whether to count
                //  $data['ot'] =  $sit ? "Entered in that day's form" : "Enter in OT Form";//'Punching excused Use DutyForm to enter for the day',
                $data['ot'] = "*";
            }

            $dates[] = $data;
        }


        return [
            'sittingsWithPunchok' => $sittingsWithPunchok,
            // 'sittingsWithNoPunching' => $sittingsWithNoPunching,
            'sittingsInRange' => $sittingsInRange->count(),
            'dates' =>  $dates,
        ];
    }


    //the args are set in route file web.php
    public function ajaxgetpunchtimes($date, $pen, $aadhaarid)
    {


        $tmp = strpos($pen, '-');
        if (false !== $tmp) {
            $pen = substr($pen, 0, $tmp);
        }

        $date = Carbon::createFromFormat(config('app.date_format'), $date)->format('Y-m-d');

        $day = Calender::where('date', $date)->first();

        $query =  Punching::where('date', $date);
        // $query->when ($day->punching == 'MANUALENTRY' && $pen != '' && strlen($pen) >= 5, function ($q)  use ($pen) {
        //     return $q->where('pen',$pen);
        // });
        $query->when( /* $day->punching == 'AEBAS' &&  */strlen($aadhaarid) >= 8, function ($q)  use ($aadhaarid) {
            return $q->where('aadhaarid', $aadhaarid);
        });

        // ->wherenotnull('punch_in') //prevent if only one column is available
        //  ->wherenotnull('punch_out') 
        $temp = $query->first();

        if ($temp) {
            //  Log::info($temp);


            return [
                'punchin' => $temp['punch_in'],
                'punchout' => $temp['punch_out'],
                'creator' => $temp['creator'],
                'aadhaarid' => $temp['aadhaarid'],
                'punchout_from_aebas' => $temp['punchout_from_aebas'],
                'punchin_from_aebas' => $temp['punchin_from_aebas'],

                'id' => $temp['id']
            ];
        } else return [];
    }

    private function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    public function index(Request $request)
    {
        // abort_if(Gate::denies('punching_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (request()->ajax()) {
            //Log::info($request);
            $query = Punching::query();


            if ($request->filled('datefilter')) {
                $date =  $request->query('datefilter');

                if (!$this->validateDate($date, 'Y-m-d')) {
                    $date = Carbon::createFromFormat(config('app.date_format'), $date)->format('Y-m-d');
                }

                $query = $query->where('date', $date);
            }


            // $str_datefilter = '&datefilter='.$datefilter;


            $query = $query->orderBy('date', 'DESC');


            $table = Datatables::of($query);

            $table->setRowAttr([
                'data-entry-id' => '{{$id}}',
            ]);



            $table->addColumn('actions', '&nbsp;')->rawColumns(['actions']);;
            $table->editColumn('actions', function ($row) {
                $gateKey  = 'punching_';
                $routeKey = 'admin.punchings';

                return view('actionsTemplate', compact('row', 'gateKey', 'routeKey'));
            });


            return $table->make(true);
        }

        return view('admin.punchings.index');
    }

    public function create()
    {
        // abort_if(Gate::denies('punching_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $forms = PunchingForm::pluck('creator', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.punchings.create');
    }

    public function store(Request $request)
    {
        $punching = Punching::create($request->all());

        return redirect()->route('admin.punchings.index');
    }

    public function edit(Punching $punching)
    {


        // abort_if(Gate::denies('punching_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //  $forms = PunchingForm::pluck('creator', 'id')->prepend(trans('global.pleaseSelect'), '');

        //  $punching->load('form');

        return view('admin.punchings.edit', compact('punching'));
        //return view('admin.punchings.edit', compact('punching'));
    }

    public function update(Request $request, Punching $punching)
    {


        $punching->update(
            [
                'pen' => $request['pen'],
                'punch_in' => !$punching->punchin_from_aebas ? $request['punch_in'] : $punching->punch_in,
                'punch_out' =>  !$punching->punchout_from_aebas ?  $request['punch_out'] : $punching->punch_out,
            ]

        );
        $reportdate = Carbon::createFromFormat('Y-m-d', $punching->date)->format(config('app.date_format'));

        return redirect()->route('admin.punchings.index', ['datefilter' => $reportdate]);
    }

    public function show(Punching $punching)
    {
        // abort_if(Gate::denies('punching_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        return view('admin.punchings.show', compact('punching'));
    }

    public function destroy(Punching $punching)
    {
        // abort_if(Gate::denies('punching_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $punching->delete();

        return back();
    }
   

    public function fetch($reportdate)
    {
        $insertedcount =(new PunchingService())->fetchSuccessAttendance($reportdate);
        
        \Session::flash('message-success', "Fetched\Processed: {$insertedcount} records for {$reportdate}");

        return view('admin.calenders.index');
    }
    ////

    public function fetchApi(Request $request)
    {
        $apinum = $request->query('apinum');
        $reportdate = $request->query('reportdate', '01-01-2000');
        $data = (new PunchingService())->fetchApi( $apinum, $reportdate );
        
        if (!count($data)) {

            \Session::flash('message-danger', "No Data");
            return view('admin.punchings.index');
        }


        $list = array_values($data);
        //  dd( $list ); # add headers for each column in the CSV download
        array_unshift($list, array_keys($data[0]));


        $callback = function () use ($list) {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',   'Content-type'        => 'text/csv',   'Content-Disposition' => "attachment; filename={$returnkey}.csv",   'Expires'             => '0',   'Pragma'              => 'public'
        ];

        return response()->stream($callback, 200, $headers);
    }
}
