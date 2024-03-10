<?php

namespace App\Http\Requests\Admin;

use App\SectionEmployee;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSectionEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('section_employee_edit');
    }

    public function rules()
    {
        return [
            'section_or_offfice_id' => [
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
