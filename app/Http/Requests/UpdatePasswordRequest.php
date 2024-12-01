<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
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
          'password' => ['required', 'string','min:8', 'max:20', 'confirmed'],
          'password_confirmation' => ['']
        ];
    }
    public function messages()
    {
      return [
        'password.required' => __('validation.password.required'),
        'password.min' => __('validation.password.required'),
        'password.max' => __('validation.password.required'),
        'password.confirmed' => __('validation.password.confirmed'),
      ];
    }
}
