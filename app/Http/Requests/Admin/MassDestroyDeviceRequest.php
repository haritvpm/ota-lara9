<?php

namespace App\Http\Requests\Admin;

use App\Device;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDeviceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('device_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:devices,id',
        ];
    }
}
