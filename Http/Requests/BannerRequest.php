<?php

namespace Modules\Banners\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'  => 'required|max:191',
            'image' => "nullable|max:191",
            'url' => "nullable", 
            'status' => "nullable|in:active,inactive", 
            'target' => "nullable|in:_blank,_self,_parent,_top", 
            'order' => "nullable|numeric", 
            'summary_01' => "nullable", 
            'summary_02' => "nullable", 
            'menu_id' => "required|numeric"
        ];
    }
}