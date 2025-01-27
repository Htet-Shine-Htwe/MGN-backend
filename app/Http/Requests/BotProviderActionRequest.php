<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotProviderActionRequest extends FormRequest
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
            'bot_publisher_id' => "required|integer|exists:bot_publishers,id",
            "bot_social_channels" => "required|array",
            "stack_id" => "required|integer|exists:stacks,id",
            "mogou_id" => "required|integer|exists:mogous,id",
            "sub_mogou_id" => "required|integer|exists:sub_mogous,id",
        ];
    }
}
