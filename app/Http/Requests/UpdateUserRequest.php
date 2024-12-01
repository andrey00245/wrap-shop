<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
          'name' => ['required', 'string','min:3', 'max:12'],
          'last_name' => ['string', 'min:3', 'max:15'],
          'email' => ['required', 'string', 'email', 'min:3', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
          'phone' => ['required', 'string','regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        ];
    }
    public function messages()
    {
      return [
        'name.required' => __('validation.name.required'),
        'name.min' => __('validation.name.required'),
        'name.max' => __('validation.name.required'),
        'name.string' => __('validation.name.required'),
        'last_name.min' => __('validation.last_name.required'),
        'last_name.max' => __('validation.last_name.required'),
        'last_name.string' => __('validation.last_name.required'),
        'email.required' => __('validation.email.email'),
        'email.string' => __('validation.email.email'),
        'email.email' => __('validation.email.email'),
        'email.unique' => __('validation.email.unique'),
        'email.min' => __('validation.email.email'),
        'email.max' => __('validation.email.email'),
        'phone.required' => __('validation.phone.required'),
        'phone.unique' => __('validation.phone.regex'),
        'phone.regex' => __('validation.phone.regex'),
        'phone.min' => __('validation.phone.regex'),
        'phone.max' => __('validation.phone.regex'),
      ];
    }
}
