<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialChannelActionRequest extends FormRequest
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
            'bot_id' => 'required|exists:bot_publishers,id',
            'token_key' => 'required|string',
            'bot_type' => 'required|string',
        ];
    }
}
