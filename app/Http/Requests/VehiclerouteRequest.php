<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use App\Models\Vehicleroutes;
use Carbon\Carbon;

class VehiclerouteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules()
    {
        return [
            'vehicle_id' => 'required|exists:vehicles,id',
            'route_id' => 'required|exists:routes,id',
            'date_from' => 'required|date|before_or_equal:date_to',
            'date_to' => 'required|date|after_or_equal:date_from',
        ];
    }

    public function messages()
    {
        return [
            'date_from.before_or_equal' => 'La fecha desde no puede ser mayor a la fecha hasta.',
            'date_to.after_or_equal' => 'La fecha hasta no puede ser menor a la fecha desde.',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $vehicleId = $this->input('vehicle_id');
            $routeId = $this->input('route_id');
            $dateFrom = Carbon::parse($this->input('date_from'));
            $dateTo = Carbon::parse($this->input('date_to'));

            for ($date = $dateFrom; $date->lte($dateTo); $date->addDay()) {
                $existingRecord = Vehicleroutes::where('vehicle_id', $vehicleId)
                    ->where('route_id', $routeId)
                    ->whereDate('date', $date->format('Y-m-d'))
                    ->exists();

                if ($existingRecord) {
                    $validator->errors()->add('date_from', 'El vehículo ya tiene un registro para la ruta y fecha ' . $date->format('d-m-Y'));
                    break;
                }
            }
        });
    }
}
