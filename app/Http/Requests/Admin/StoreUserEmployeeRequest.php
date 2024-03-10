<?php

namespace App\Http\Requests\Admin;

use App\UserEmployee;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreUserEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_employee_create');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'employee_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
