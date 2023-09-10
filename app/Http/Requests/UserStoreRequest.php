<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'name' => 'sometimes|required|min:3',
            'email' => 'sometimes|unique:users,email',
            'password' => 'sometimes|required|min:6',
            'order' => 'sometimes|required',
            'active' => 'sometimes',
        ];
    }
}
