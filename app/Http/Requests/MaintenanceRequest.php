<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MaintenanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
                Rule::unique('maintenances', 'name')->ignore($this->maintenance),
            ],
            'initial_date' => 'required|date|before_or_equal:final_date',
            'final_date' => 'required|date|after_or_equal:initial_date',
        ];
    }
}
