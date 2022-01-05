<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Warning;
class NewWarningRequest extends FormRequest
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
            'date'=>'required|date_format:Y-m-d',
            'location'=>'required',
            'address'=>['required', function ($attribute, $value, $fail) {
                $address = $this->input('address','')." ".$this->input('suburb','')." ".$this->input('state','');
                if( Warning::getCoordinates($address) == false ){
                    $fail('The '.$attribute.' is invalid.');
                }
            }],
            'suburb'=>'required',
            'state'=>'required',
            'advice'=>'required',
            'start_time'=>'required',
            'end_time'=>'required',
           'comments'=>'required|max:150',
            'source'=>'required',
            'positive_case_date'=>'required|date_format:Y-m-d',
            'positive_case_type'=>'required',
            'submitted_by'=>'required',
            'email'=>'required|email',
        ];
    }
}
