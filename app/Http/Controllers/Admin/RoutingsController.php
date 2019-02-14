<?php

namespace App\Http\Controllers\Admin;

use App\Routing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoutingsRequest;
use App\Http\Requests\Admin\UpdateRoutingsRequest;
use Yajra\DataTables\DataTables;

class RoutingsController extends Controller
{
    /**
     * Display a listing of Routing.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('routing_access')) {
            return abort(401);
        }
        
        $nolegsecttusers = \Config::get('custom.show_legsectt');

        $usersoflegsectt = null;

        if(!$nolegsecttusers){
            $usersoflegsectt  = \App\User::SimpleOrHiddenUsers()
            ->pluck('id');
        }

        $query = Routing::query();
   

        $query->when($nolegsecttusers == false, function ($q) use ($usersoflegsectt){
          return $q->whereNotIn('user_id', $usersoflegsectt);
                 
        });

        $routings = $query->latest()->get();




        return view('admin.routings.index', compact('routings'));
    }

    /**
     * Show the form for creating new Routing.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('routing_create')) {
            return abort(401);
        }
        
        $users = \App\User::get()->pluck('username', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        return view('admin.routings.create', compact('users'));
    }

    /**
     * Store a newly created Routing in storage.
     *
     * @param  \App\Http\Requests\StoreRoutingsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoutingsRequest $request)
    {
        if (! Gate::allows('routing_create')) {
            return abort(401);
        }

        //check if the routes in the input are valid

 
        // $users = explode(',', $request['route'] );

        // foreach ($users as $user) {
        //     if(\App\User::where('username',$user)->first() === null ){

        //         return  redirect()->back()
        //                     ->witherrors(['Unknown user: '.$user]);
        //     }
        // }


        $routing = Routing::create($request->all());



        return redirect()->route('admin.routings.index');
    }


    /**
     * Show the form for editing Routing.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('routing_edit')) {
            return abort(401);
        }
        
        $users = \App\User::get()->pluck('username', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        $routing = Routing::findOrFail($id);

        return view('admin.routings.edit', compact('routing', 'users'));
    }

    /**
     * Update Routing in storage.
     *
     * @param  \App\Http\Requests\UpdateRoutingsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoutingsRequest $request, $id)
    {
        if (! Gate::allows('routing_edit')) {
            return abort(401);
        }
        $routing = Routing::findOrFail($id);
        $routing->update($request->all());



        return redirect()->route('admin.routings.index');
    }


    /**
     * Display Routing.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('routing_view')) {
            return abort(401);
        }
        $routing = Routing::findOrFail($id);

        return view('admin.routings.show', compact('routing'));
    }


    /**
     * Remove Routing from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('routing_delete')) {
            return abort(401);
        }
        $routing = Routing::findOrFail($id);
        $routing->delete();

        return redirect()->route('admin.routings.index');
    }

    /**
     * Delete all selected Routing at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('routing_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Routing::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}
