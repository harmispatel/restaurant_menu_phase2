<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopRoomRequest extends FormRequest
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
                'floor' => 'required|numeric',
                'room_no' => 'required|unique:shop_rooms,room_no,' . $this->id . ',id,floor,' . $this->floor . ',shop_id,' . $this->shop_id,
                'staffs'   => 'required|array|min:1',
            ];
        }else{

            return [
                'shop_id' => 'required|exists:shops,id',
                'floor' => 'required|numeric',
                'room_no' => 'required|unique:shop_rooms,room_no,NULL,id,floor,' . $this->floor . ',shop_id,' . $this->shop_id,
                'staffs'   => 'required|array|min:1',
            ];
        }
    }
}
