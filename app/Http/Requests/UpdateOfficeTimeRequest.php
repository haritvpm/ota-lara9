<?php

namespace App\Http\Requests;

use App\OfficeTime;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateOfficeTimeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('designation_create');
    }

    public function rules()
    {
        return [
            'groupname' => [
                'string',
                'required',
                'unique:office_times,groupname,' . request()->route('office_time')->id,
            ],
            // 'fn_from' => [
            //     'date_format:' . config('panel.time_format'),
            //     'nullable',
            // ],
            // 'an_to' => [
            //     'date_format:' . config('panel.time_format'),
            //     'nullable',
            // ],
            'minutes_for_ot_workingday' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'minutes_for_ot_holiday' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'max_ot_workingday' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'max_ot_sittingday' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'max_ot_holiday' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'office_minutes' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
