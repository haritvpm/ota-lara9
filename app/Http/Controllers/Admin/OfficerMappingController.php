<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\Admin\MassDestroyOfficerMappingRequest;
use App\Http\Requests\Admin\StoreOfficerMappingRequest;
use App\Http\Requests\Admin\UpdateOfficerMappingRequest;
use App\OfficerMapping;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class OfficerMappingController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('officer_mapping_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $officerMappings = OfficerMapping::with(['section_or_officer_user', 'controlling_officer_user'])->get();

        return view('admin.officerMappings.index', compact('officerMappings'));
    }

    public function create()
    {
        abort_if(Gate::denies('officer_mapping_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $section_or_officer_users = User::Officers()->get()->mapWithKeys(function ($item) {

            return [$item['id'] => $item->title . ' (' . $item->username .')'  ];
        
        })->prepend(trans('global.pleaseSelect'), '');;

        $controlling_officer_users = $section_or_officer_users->filter(function ($value, $key) {
            return !Str::startsWith($value, 'sn.');
        });

        return view('admin.officerMappings.create', compact('controlling_officer_users', 'section_or_officer_users'));
    }

    public function store(StoreOfficerMappingRequest $request)
    {
        $officerMapping = OfficerMapping::create($request->all());

        return redirect()->route('admin.officer-mappings.index');
    }

    public function edit(OfficerMapping $officerMapping)
    {
        abort_if(Gate::denies('officer_mapping_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

      //  $section_or_officer_users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

       // $controlling_officer_users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
       $section_or_officer_users = User::Officers()->get()->mapWithKeys(function ($item) {

            return [$item['id'] => $item->title . ' (' . $item->username .')'  ];
        
        })->prepend(trans('global.pleaseSelect'), '');;

        $controlling_officer_users = $section_or_officer_users->filter(function ($value, $key) {
            return !Str::startsWith($value, 'sn.');
        });

        $officerMapping->load('section_or_officer_user', 'controlling_officer_user');

        return view('admin.officerMappings.edit', compact('controlling_officer_users', 'officerMapping', 'section_or_officer_users'));
    }

    public function update(UpdateOfficerMappingRequest $request, OfficerMapping $officerMapping)
    {
        $officerMapping->update($request->all());

        return redirect()->route('admin.officer-mappings.index');
    }

    public function show(OfficerMapping $officerMapping)
    {
        abort_if(Gate::denies('officer_mapping_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $officerMapping->load('section_or_officer_user', 'controlling_officer_user');

        return view('admin.officerMappings.show', compact('officerMapping'));
    }

    public function destroy(OfficerMapping $officerMapping)
    {
        abort_if(Gate::denies('officer_mapping_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $officerMapping->delete();

        return back();
    }

    public function massDestroy(MassDestroyOfficerMappingRequest $request)
    {
        $officerMappings = OfficerMapping::find(request('ids'));

        foreach ($officerMappings as $officerMapping) {
            $officerMapping->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
