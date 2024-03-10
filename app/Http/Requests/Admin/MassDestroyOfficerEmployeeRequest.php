<?php

namespace App\Http\Requests\Admin;

use App\OfficerEmployee;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyOfficerEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('officer_employee_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:officer_employees,id',
        ];
    }
}
