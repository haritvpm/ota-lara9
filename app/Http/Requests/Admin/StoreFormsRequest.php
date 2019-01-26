<?php
namespace App\Http\Requests\Admin;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;
use JavaScript;
class StoreFormsRequest extends FormRequest
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
        return true;
       /*
        return [
            'session' => 'required',
            'overtime_slot' => 'required',
            'duty_date' => 'nullable|date_format:'.config('app.date_format'),
            'date_from' => 'nullable|date_format:'.config('app.date_format'),
            'date_to' => 'nullable|date_format:'.config('app.date_format'),
            'creator' => 'min:2|required',
        ];*/


    }
        /**
    * Configure the validator instance.
    *
    * @param  \Illuminate\Validation\Validator  $validator
    * @return void
    */
    /*
    public function withValidator($validator)
    {
        if ( $validator->fails() ) {
            // Handle errors
        }

        return response()
        ->json([
            'products_empty' => ['One or more Product is required.']
        ], 422);
        
             
        $validator->after(function ($validator) 
        {
            //if ($this->somethingElseIsInvalid()) 
            {
             //  $validator->errors()->add('field', 'Something is wrong with this field!');
            }
        });
    }*/
    
}
