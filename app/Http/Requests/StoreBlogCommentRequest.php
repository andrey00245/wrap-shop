<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'guest_name' => ['required', 'string', 'max:120'],
            'guest_email' => ['required', 'email:rfc,dns', 'max:190'],
            'body' => ['required', 'string', 'max:8000'],
            'comment_hp' => ['prohibited'],
        ];
    }
}
