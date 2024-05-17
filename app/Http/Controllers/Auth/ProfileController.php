<?php

namespace App\Http\Controllers\Auth;

use Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Where to redirect users after displayname is changed.
     *
     * @var string $redirectTo
     */
    protected $redirectTo = '/change_displayname';

    /**
     * Change password form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showChangeDisplaynameForm()
    {
        $name = Auth::user()->Title;
        $displayname = Auth::user()->displayname;

        return view('auth.change_displayname', compact('name','displayname'));
    }

    /**
     * Change password.
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function changeDisplayname(Request $request)
    {
        $user = Auth::getUser();
        $this->validator($request->all())->validate();
        
        $user->displayname = $request->get('displayname');
        $user->save();
        return redirect($this->redirectTo)->with('success', 'Name change successfully!');
   
    }

    /**
     * Get a validator for an incoming change password request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
           
            'displayname' => 'regex:/^[A-Za-z\s\-_.,]+$/',
        ]);
    }
}
