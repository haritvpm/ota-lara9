<?php

namespace App\Http\Requests\Admin;

use App\Device;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDeviceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('device_access');
    }

    public function rules()
    {
        return [
            'device' => [
                'string',
                'required',
                'unique:devices,device,' . request()->route('device')->id,
            ],
            'loc_name' => [
                'string',
                'nullable',
            ],
            'entry_name' => [
                'string',
                'nullable',
            ],
        ];
    }
}
