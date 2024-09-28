<?php

namespace App\Http\Requests;

use App\Enum\MogouFinishStatus;
use App\Vaildations\MogouValidation;
use Illuminate\Foundation\Http\FormRequest;

class MogouActionRequest extends FormRequest
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
            'title' => 'required',
            'description' => 'required',
            'status' =>  MogouValidation::status(),
            'author' => 'nullable',
            'cover' => 'nullable|file',
            'legal_age' => 'required|boolean',
            'rating' => 'required|numeric|between:0,5',
            'finish_status' => MogouValidation::finishStatus(),
            'mogou_type' => MogouValidation::mogouType(true),
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'released_year' => 'nullable|integer',
            'released_at' => 'nullable|date',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.in' => MogouValidation::invalidStatusMessages(),
            'finish_status.in' => MogouValidation::invalidFinishStatusMessages(),
        ];
    }
}
