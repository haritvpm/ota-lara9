<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSessionsRequest extends FormRequest
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
            'name' => 'min:2|required|unique:sessions,name,'.$this->route('session'),
            'kla' => 'max:2147483647|required|numeric',
            'session' => 'max:2147483647|required|numeric',
            'dataentry_allowed' => 'required',
        ];
    }
}
