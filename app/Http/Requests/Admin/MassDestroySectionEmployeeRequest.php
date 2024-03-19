<?php

namespace App\Http\Requests\Admin;

use App\SectionEmployee;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySectionEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('section_employee_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:section_employees,id',
        ];
    }
}
