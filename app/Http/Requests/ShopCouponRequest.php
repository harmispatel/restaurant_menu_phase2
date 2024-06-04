<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ShopCouponRequest extends FormRequest
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
        $today = Carbon::today()->toDateString();
        if($this->id){
            $rules = [
                'code' => 'required|unique:shop_coupons,code,'.$this->id,
                'name' => 'required',
                'value' => 'required',
                'total_users' => 'required',
                'type' => 'required',
                'end_date' => 'required|after:' . $today,
            ];

        }else{

            $rules = [
                'code' => 'required|unique:shop_coupons,code',
                'name' => 'required',
                'value' => 'required',
                'total_users' => 'required',
                'type' => 'required',
                'end_date' => 'required|after:' . $today,
            ];
        }

        return $rules;
    }
}
