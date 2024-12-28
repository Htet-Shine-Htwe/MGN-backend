<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubMogouActionRequest extends FormRequest
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
            'title' => 'required|string',
            'description' => 'required|string',
            'cover' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:8192',
            'status' => 'nullable',
            'chapter_number' => 'required|integer',
            'subscription_only' => 'required|boolean',
            'subscription_collection' => 'nullable|json',
            'mogou_id' => 'required|integer|exists:mogous,id',
        ];
    }
}
