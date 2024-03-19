<?php

namespace App\Http\Requests\Admin;

use App\Section;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSectionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('section_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:sections',
            ],
        ];
    }
}
