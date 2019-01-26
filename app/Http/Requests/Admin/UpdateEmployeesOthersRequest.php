<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeesOthersRequest extends FormRequest
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
            
            'name' => 'min:3|required',
            'pen' => 'min:3|required',
            'designation_id' => 'required',
            'account_type' => 'required',
         //   'ifsc' => 'min:11|max:11',
            'account_no' => 'min:7|required',
            'mobile' => 'min:10|required',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            //see if a user with same pen exists

            //this means we cannot edit ourselves
            /*
            $emp = \App\EmployeesOther::where('added_by', \Auth::user()->username )
                        ->where('pen', $this->input('pen'))
                        
                        ->count();


            if ($emp > 0) {
                $validator->errors()->add('pen', 'A person with the same PEN already exists');
            }
            

            $emp = \App\EmployeesOther::where('added_by', \Auth::user()->username )
                        ->where('account_no', $this->input('account_no'))->count();

            if ($emp > 0) {
                $validator->errors()->add('account_no', 'A person with the same account_no already exists');
            }*/

           if(  $this->input('account_type') == "Bank Account" ){
                if( 11 != strlen($this->input('ifsc'))){
                    $validator->errors()->add('ifsc', 'IFSC must be 11 character long');
                }
                else if( 0 == strncasecmp($this->input('ifsc'), "sbtr", 4)){
                    $validator->errors()->add('ifsc', 'SBT IFSC no longer valid');
                }

            } else  if(  $this->input('account_type') == "TSB" ){
                if( 0 != strlen($this->input('ifsc'))){
                    $validator->errors()->add('ifsc', 'No IFSC needed for TSB accounts');
                }
            }

        });
    }
}
