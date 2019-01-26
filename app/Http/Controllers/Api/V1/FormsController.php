<?php

namespace App\Http\Controllers\Api\V1;

use App\Form;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFormsRequest;
use App\Http\Requests\Admin\UpdateFormsRequest;
use Yajra\DataTables\DataTables;

class FormsController extends Controller
{
    public function index()
    {
        return Form::all();
    }

    public function show($id)
    {
        return Form::findOrFail($id);
    }

    public function update(UpdateFormsRequest $request, $id)
    {
        $form = Form::findOrFail($id);
        $form->update($request->all());
        

        return $form;
    }

    public function store(StoreFormsRequest $request)
    {
        $form = Form::create($request->all());
        

        return $form;
    }

    public function destroy($id)
    {
        $form = Form::findOrFail($id);
        $form->delete();
        return '';
    }
}
