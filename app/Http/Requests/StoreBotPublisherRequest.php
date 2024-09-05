<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBotPublisherRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'token_key' => ['required', 'string', 'max:255','unique:bot_publishers,token_key'],
            'type' => ['required', 'max:255'],
            // 'available_ids' => ['required', 'array'],
            'available_ids' => ['required', 'string', 'max:255'],
        ];
    }
}
