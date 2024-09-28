<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportActionRequest extends FormRequest
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
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'current_url' => 'required|string',
            'image' => 'nullable|file|max:4096|mimes:jpeg,png,jpg',
            'user_id' => 'nullable|numeric',
        ];
    }
}
