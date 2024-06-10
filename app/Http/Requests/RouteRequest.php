<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $routeId = $this->route('route');

        return [
            'latitude_start' => [
                'required',
                'numeric',
                'between:-90,90',
                Rule::unique('routes')->ignore($routeId)->where(function ($query) {
                    return $query->where('latitude_end', $this->latitude_end)
                        ->where('longitude_start', $this->longitude_start)
                        ->where('longitude_end', $this->longitude_end)
                        ->where('latitude_start', '!=', $this->latitude_end);
                })
            ],
            'longitude_start' => [
                'required',
                'numeric',
                'between:-180,180',
                Rule::unique('routes')->ignore($routeId)->where(function ($query) {
                    return $query->where('latitude_end', $this->latitude_end)
                        ->where('longitude_start', $this->longitude_start)
                        ->where('longitude_end', $this->longitude_end)
                        ->where('longitude_start', '!=', $this->longitude_end);
                })
            ],
            'latitude_end' => 'required|numeric|between:-90,90',
            'longitude_end' => 'required|numeric|between:-180,180',
            'status' => 'required|boolean',
            'name' => [
                'required',
                'min:3',
                'max:50'
            ],
        ];
    }
}