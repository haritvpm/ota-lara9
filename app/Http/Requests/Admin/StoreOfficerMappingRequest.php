<?php

namespace App\Http\Requests\Admin;

use App\OfficerMapping;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOfficerMappingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('officer_mapping_create');
    }

    public function rules()
    {
        return [
            'controlling_officer_user_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
