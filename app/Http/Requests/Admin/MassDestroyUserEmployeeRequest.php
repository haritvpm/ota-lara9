<?php

namespace App\Http\Requests\Admin;

use App\UserEmployee;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyUserEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('user_employee_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:user_employees,id',
        ];
    }
}
