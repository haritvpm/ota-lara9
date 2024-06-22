<?php

namespace App\Http\Requests;

use App\ShiftTime;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreShiftTimeRequest extends FormRequest
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
                'unique:shift_times',
            ],
            'shift_minutes' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'minutes_for_ot' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
