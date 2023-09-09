<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleStoreRequest extends FormRequest
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
            'name' => 'sometimes|required|max:255',
            'category_id' => 'sometimes|required',
            'active' => 'sometimes|required',
            'order' => 'sometimes|required',
            'slug' => 'sometimes|unique:articles,slug',
            "updated_at" => 'sometimes|required',
        ];
    }
}
