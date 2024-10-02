<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
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
            'user_code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'password' => 'required|string|min:8',
            'current_subscription_id' => 'required|exists:subscriptions,id',
        ];
    }
}
