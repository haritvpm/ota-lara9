<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExemptionformsRequest extends FormRequest
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
            
            'session' => 'required',
            'creator' => 'min:2|required',
            'owner' => 'required',
            'form_no' => 'max:2147483647|required|numeric|unique:exemptionforms,form_no,'.$this->route('exemptionform'),
            'submitted_on' => 'nullable|date_format:'.config('app.date_format'),
        ];
    }
}
