<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubMogouDraftRequest extends FormRequest
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
            'id' => 'nullable|integer',
            'title' => 'required|string',
            'chapter_number' => 'required|integer',
            'mogou_slug' => "required|string|exists:mogous,slug",
            "description" => "required|string",
            "third_party_url" => "nullable|string|min:5",
            "subscription_only" => "required|boolean",
            'third_party_redirect' => 'required|boolean',
        ];
    }
}
