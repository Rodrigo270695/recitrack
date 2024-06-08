<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VehicleRequest extends FormRequest
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
            Rule::unique('vehicles', 'name')->ignore($this->vehicle),
        ],
        'code' => [
            'required',
            'min:3',
            'max:10',
            Rule::unique('vehicles', 'code')->ignore($this->vehicle),
        ],
        'plate' => [
            'required',
            'min:3',
            'max:10',
            Rule::unique('vehicles', 'plate')->ignore($this->vehicle),
        ],
        'year' => 'required|integer|min:1900|max:' . now()->year,
        'description' => 'nullable|string',
        'capacity' => 'required|integer|min:1',
        'status' => [
            'required',
            Rule::in(['Disponible', 'No Disponible', 'En Mantenimiento']),
        ],
        'brandmodel_id' => 'required|exists:brandmodels,id',
        'vehicletype_id' => 'required|exists:vehicletypes,id',
        'vehiclecolor_id' => 'required|exists:vehiclecolors,id',

        ];
    }
}
