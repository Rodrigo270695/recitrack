<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'min:3',
                'max:50',
                Rule::unique('brands')->ignore($this->brand ? $this->brand->id : null),
            ],
            'description' => 'nullable|string',
            'logo' => 'nullable|max:3072',
        ];
    }
}
