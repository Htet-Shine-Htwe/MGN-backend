<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialInfoRequest extends FormRequest
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
            'name' => 'required|string',
            'type' => 'required|string',
            'icon' => 'required|string',
            'cover_photo' => 'nullable|file|mimes:jpeg,jpg,png,gif|max:4096',
            'redirect_url' => 'required|url',
            'text_url' => 'nullable|url',
            'active' => 'nullable|boolean'
        ];
    }
}
