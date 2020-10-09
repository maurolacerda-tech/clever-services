<?php

namespace Modules\Services\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'  => 'required|max:191',
            'slug' => "nullable",
            'image' => "nullable|max:191",
            'image2' => "nullable|max:191",
            'icon' => "nullable",
            'summary' => "nullable", 
            'status' => "nullable|in:active,inactive",
            'featured' => "nullable|in:active,inactive",             
            'order' => "nullable|numeric",            
            'body' => "nullable",
            'seo_title' => "nullable",
            'meta_description' => "nullable",
            'meta_keywords' => "nullable",
            'menu_id' => "required|numeric",
            'parent_id' => "nullable|numeric"
        ];
    }
}