<?php

namespace App\Http\Controllers\Api\V1;

use App\Routing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoutingsRequest;
use App\Http\Requests\Admin\UpdateRoutingsRequest;
use Yajra\DataTables\DataTables;

class RoutingsController extends Controller
{
    public function index()
    {
        return Routing::all();
    }

    public function show($id)
    {
        return Routing::findOrFail($id);
    }

    public function update(UpdateRoutingsRequest $request, $id)
    {
        $routing = Routing::findOrFail($id);
        $routing->update($request->all());
        

        return $routing;
    }

    public function store(StoreRoutingsRequest $request)
    {
        $routing = Routing::create($request->all());
        

        return $routing;
    }

    public function destroy($id)
    {
        $routing = Routing::findOrFail($id);
        $routing->delete();
        return '';
    }
}
