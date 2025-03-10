<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //https://www.itsolutionstuff.com/post/laravel-unique-validation-on-update-exampleexample.html
            'login' => 'required|unique:users,login,' . $this->route('id'),
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,' . $this->route('id'),
            'password' => 'required',
        ];
    }
}
