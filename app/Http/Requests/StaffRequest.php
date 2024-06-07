<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
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
        if($this->id)
        {
            $rules = [
                'name' => 'required',
                'email' => 'required|email|unique:staffs,email,'.$this->id,
                'wp_number' => 'required|unique:staffs,wp_number,'.$this->id,
                'type' => 'required'
            ];

        }
        else{

            $rules = [
                'name' => 'required',
                'email' => 'required|email|unique:staffs,email',
                'wp_number' => 'required|unique:staffs,wp_number',
                'type' => 'required'
            ];
        }

        return $rules;
    }
}
