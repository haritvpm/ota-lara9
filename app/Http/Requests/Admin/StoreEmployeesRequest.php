<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeesRequest extends FormRequest
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
            'name' => 'min:3|required|regex:/^[a-zA-Z .]*$/',
            /*'name_mal' => array(
                'required',
                'regex:/^((?![A-Za-z0-9]).)*$/'
                ),*/
            'pen' => 'min:6|required|unique:employees,pen,'.$this->route('employee'),
            'designation_id' => 'required',
        ];
    }
}
