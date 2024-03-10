<?php

namespace App\Http\Requests\Admin;

use App\OfficerEmployee;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOfficerEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('officer_employee_create');
    }

    public function rules()
    {
        return [
            'officer_id' => [
                'required',
                'integer',
            ],
            'employee_id' => [
                'required',
                'integer',
            ],
            'date_from' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'date_to' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
