<?php

namespace App\Http\Requests\Admin;

use App\OfficerMapping;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyOfficerMappingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('officer_mapping_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:officer_mappings,id',
        ];
    }
}
