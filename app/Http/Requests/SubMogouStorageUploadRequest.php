<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubMogouStorageUploadRequest extends FormRequest
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
            'mogou_id' => 'required|integer|exists:mogous,id',
            'sub_mogou_slug' => 'required',
            'upload_files' => 'required|array',
            'upload_files.*' => 'required|array',
            'upload_files.*.file' => 'required|image',
            'upload_files.*.page_number' => 'required|integer',
            'watermark_apply' => 'required|boolean'
        ];
    }
}
