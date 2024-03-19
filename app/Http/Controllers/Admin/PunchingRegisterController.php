<?php

namespace App\Http\Controllers\Admin;

use App\Employee;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePunchingRegisterRequest;
use App\Http\Requests\UpdatePunchingRegisterRequest;
use App\OfficerMapping;
use App\Section;
use App\Punching;
use App\PunchingRegister;
use App\SectionEmployee;
use App\UserEmployee;
use Gate;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use JavaScript;

class PunchingRegisterController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('punching_register_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //get sections controlled by this officer
        $me = Auth::user();
        $users_under = OfficerMapping::where('controlling_officer_user_id', $me->id)->pluck('section_or_officer_user_id');

        $emps = collect();
        //add the section officers
        $officers_under = UserEmployee::with('employee')->wherein('user_id', $users_under)->get();
        $emps = $emps->concat($officers_under->pluck('employee.id'));

        //get list of sections under these users_under
        $sections = Section::wherein('officer_id', $users_under)->get();
        $section_employees = SectionEmployee::with(['employee', 'section_or_offfice'])->wherein('section_or_offfice_id', $sections->pluck('id'))->get();
        $emps = $emps->concat($section_employees->pluck('employee.id'));
       // dd($section_employees);

        $punchingRegisters = null;
        if ($me->isITAdmin()) {
            $punchingRegisters = PunchingRegister::with(['employee', 'punchin'])
                ->get();
                
        } else {
            $punchingRegisters = PunchingRegister::with(['employee', 'punchin'])
                ->wherein('employee_id', $emps)->get();
             
        }

        JavaScript::put([
            'section_employees' => $section_employees,
        
        ]);
        return view('admin.punchingRegisters.index', compact('punchingRegisters', 'section_employees', 'officers_under'));
    }

    public function create()
    {
        abort_if(Gate::denies('punching_register_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employees = Employee::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $punchins = Punching::pluck('date', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.punchingRegisters.create', compact('employees', 'punchins'));
    }

    public function store(StorePunchingRegisterRequest $request)
    {
        $punchingRegister = PunchingRegister::create($request->all());

        return redirect()->route('admin.punching-registers.index');
    }

    public function edit(PunchingRegister $punchingRegister)
    {
        abort_if(Gate::denies('punching_register_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employees = Employee::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $punchins = Punching::pluck('date', 'id')->prepend(trans('global.pleaseSelect'), '');

        $punchingRegister->load('employee', 'punchin');

        return view('admin.punchingRegisters.edit', compact('employees', 'punchingRegister', 'punchins'));
    }

    public function update(UpdatePunchingRegisterRequest $request, PunchingRegister $punchingRegister)
    {
        $punchingRegister->update($request->all());

        return redirect()->route('admin.punching-registers.index');
    }

    public function show(PunchingRegister $punchingRegister)
    {
        abort_if(Gate::denies('punching_register_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $punchingRegister->load('employee', 'punchin');

        return view('admin.punchingRegisters.show', compact('punchingRegister'));
    }
}
