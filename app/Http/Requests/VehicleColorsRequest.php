<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VehicleColorsRequest extends FormRequest
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
                Rule::unique('vehiclecolors')->ignore($this->route('vehiclecolor')),
            ],
            'description' => 'nullable|string',
        ];
    }
}
