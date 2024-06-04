<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopTableRequest extends FormRequest
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
        if($this->id){
            return [
                'shop_id' => 'required|exists:shops,id',
                'shop_area' => 'required',
                'table_name' => 'required|unique:shop_tables,table_name,' . $this->id . ',id,shop_id,' . $this->shop_id,
                'staffs'   => 'required|array|min:1',

            ];
        }else{
            return [
                'shop_id' => 'required|exists:shops,id',
                'shop_area' => 'required',
                'table_name' => 'required|unique:shop_tables,table_name,NULL,id,shop_id,'.$this->shop_id,
                'staffs'   => 'required|array|min:1',
            ];
        }
    }
}
