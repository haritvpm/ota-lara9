<?php

namespace App\Http\Requests\Admin;

use App\UserEmployee;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateUserEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_employee_edit');
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
