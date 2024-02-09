<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoriesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            
            'category' => 'required|unique:categories,category,'.$this->route('category'),
            'normal_office_hours' => [
                'nullable',
                'integer',
                'min:0',
                'max:24',
            ],
        ];
    }
}
