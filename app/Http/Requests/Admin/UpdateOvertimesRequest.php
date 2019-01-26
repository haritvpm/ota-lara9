<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOvertimesRequest extends FormRequest
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
            
            'pen' => 'min:4|required',
            'designation' => 'min:2|required',
            'form_id' => 'required',
            'from' => 'required',
            'to' => 'required',
            'count' => 'max:2147483647|required|numeric',
        ];
    }
}
