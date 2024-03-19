<?php

namespace App\Http\Requests\Admin;

use App\PunchingTrace;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePunchingTraceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('punching_trace_create');
    }

    public function rules()
    {
        return [
            'aadhaarid' => [
                'string',
                'required',
            ],
            'org_emp_code' => [
                'string',
                'nullable',
            ],
            'device' => [
                'string',
                'nullable',
            ],
            'attendance_type' => [
                'string',
                'nullable',
            ],
            'auth_status' => [
                'string',
                'nullable',
            ],
            'err_code' => [
                'string',
                'nullable',
            ],
            'att_date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'att_time' => [
                'required',
                'date_format:' . config('panel.time_format'),
            ],
        ];
    }
}
