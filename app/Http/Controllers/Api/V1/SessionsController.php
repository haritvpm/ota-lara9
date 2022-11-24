<?php

namespace App\Http\Controllers\Api\V1;

use App\Session;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSessionsRequest;
use App\Http\Requests\Admin\UpdateSessionsRequest;
use Yajra\DataTables\DataTables;

class SessionsController extends Controller
{
    public function index()
    {
        return Session::all();
    }

    public function show($id)
    {
        return Session::findOrFail($id);
    }

    public function update(UpdateSessionsRequest $request, $id)
    {
        $session = Session::findOrFail($id);
        $session->update($request->all());
        

        return $session;
    }

    public function store(StoreSessionsRequest $request)
    {
        $session = Session::create($request->all());
        

        return $session;
    }

    public function destroy($id)
    {
        $session = Session::findOrFail($id);
        $session->delete();
        return '';
    }
}
