<?php

namespace App\Http\Requests;

use App\Models\Zonecoord;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ZonecoordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'latitude' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    $longitude = $this->longitude;
                    if (Zonecoord::where('latitude', $value)->where('longitude', $longitude)->exists()) {
                        $fail('La combinación de latitud y longitud ya existe.');
                    }
                },
            ],
            'longitude' => 'required|numeric',
            'zone_id' => 'required|exists:zones,id',
        ];
    }
}
