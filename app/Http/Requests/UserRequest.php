<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'dni' => [
                'required',
                'string',
                'size:8',
                Rule::unique('users')->ignore($this->route('user')),
            ],
            'birthdate' => 'nullable|date',
            'license' => 'nullable|string|max:20',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->route('user')),
            ],
            'address' => 'nullable|string|max:100',
            'phone' => 'nullable|string',
            'typeuser_id' => 'required|exists:typeusers,id',
        ];
    }
}
