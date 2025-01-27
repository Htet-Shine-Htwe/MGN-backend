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
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email',
            'password' => 'nullable|min:6',
            'current_subscription_id' => 'nullable|exists:subscriptions,id',
            'active' => 'nullable|boolean',
            'avatar_id' => 'nullable|exists:user_avatars,id',
            'background_color' => 'nullable|string|max:155',
        ];
    }
}
