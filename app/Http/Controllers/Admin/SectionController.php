<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\Admin\MassDestroySectionRequest;
use App\Http\Requests\Admin\StoreSectionRequest;
use App\Http\Requests\Admin\UpdateSectionRequest;
use App\Section;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SectionController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('section_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sections = Section::with(['officer'])->get();

        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        abort_if(Gate::denies('section_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $officers = User::Sections()->get()->mapWithKeys(function ($item) {

            return [$item['id'] => $item->title . ' (' . $item->username .')'  ];
            
        })->prepend(trans('global.pleaseSelect'), '');;
      
        
    

        return view('admin.sections.create', compact('officers'));
    }

    public function store(StoreSectionRequest $request)
    {
        $section = Section::create($request->all());

        return redirect()->route('admin.sections.index');
    }

    public function edit(Section $section)
    {
        abort_if(Gate::denies('section_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $officers = User::Sections()->get()->mapWithKeys(function ($item) {

            return [$item['id'] => $item->title . ' (' . $item->username .')'  ];
            
        })->prepend(trans('global.pleaseSelect'), '');;
      

        $section->load('officer');

        return view('admin.sections.edit', compact('officers', 'section'));
    }

    public function update(UpdateSectionRequest $request, Section $section)
    {
        $section->update($request->all());

        return redirect()->route('admin.sections.index');
    }

    public function show(Section $section)
    {
        abort_if(Gate::denies('section_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $section->load('officer');

        return view('admin.sections.show', compact('section'));
    }

    public function destroy(Section $section)
    {
        abort_if(Gate::denies('section_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $section->delete();

        return back();
    }

    public function massDestroy(MassDestroySectionRequest $request)
    {
        $sections = Section::find(request('ids'));

        foreach ($sections as $section) {
            $section->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
