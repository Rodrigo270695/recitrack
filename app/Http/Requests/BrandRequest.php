<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BrandRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'min:3',
                'max:50',
                Rule::unique('brands')->ignore($this->route('brand')),
            ],
            'description' => 'nullable|string',
            'logo' => 'nullable|max:3072',
        ];
    }
}
