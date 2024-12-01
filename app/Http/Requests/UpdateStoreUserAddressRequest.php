<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStoreUserAddressRequest extends FormRequest
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
          'city' => ['required', 'string','min:3', 'max:255'],
          'address' => ['required', 'string', 'min:3', 'max:255'],
        ];
    }
    public function messages()
    {
      return [
        'city.required' => __('validation.field.required'),
        'city.min' => __('validation.field.required'),
        'city.max' => __('validation.field.required'),
        'city.string' => __('validation.field.required'),
        'address.min' => __('validation.field.required'),
        'address.max' => __('validation.field.required'),
        'address.string' => __('validation.field.required'),
        'address.required' => __('validation.field.required'),
      ];
    }
}
