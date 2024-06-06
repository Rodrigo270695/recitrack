<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BrandmodelRequest extends FormRequest
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
            Rule::unique('brandmodels', 'name')->ignore($this->brandmodel),
        ],
        'code' => [
            'required',
            'min:3',
            'max:50',
            Rule::unique('brandmodels', 'code')->ignore($this->brandmodel),
        ],
        'description' => 'nullable|string',
        'brand_id' => 'required|exists:brands,id',
        ];
    }
}
