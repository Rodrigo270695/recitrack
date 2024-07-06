<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class HoraryRequest extends FormRequest
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
            'day' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'start_time' => [
                'required',
                'date_format:H:i',
                'before:end_time',
                function ($attribute, $value, $fail) {
                    $endTime = request()->input('end_time');
                    $day = request()->input('day');
                    $maintenanceId = request()->input('maintenance_id');
                    $vehicleoccupantId = request()->input('vehicleoccupant_id');
                    $existingHoraries = \App\Models\Horary::where('maintenance_id', $maintenanceId)
                        ->where('vehicleoccupant_id', $vehicleoccupantId)
                        ->where('day', $day)
                        ->where(function ($query) use ($value, $endTime) {
                            $query->whereBetween('start_time', [$value, $endTime])
                                  ->orWhereBetween('end_time', [$value, $endTime])
                                  ->orWhere(function ($query) use ($value, $endTime) {
                                      $query->where('start_time', '<=', $value)
                                            ->where('end_time', '>=', $endTime);
                                  });
                        })
                        ->exists();

                    if ($existingHoraries) {
                        $fail('El horario se cruza con otro horario existente en el mismo día.');
                    }
                },
            ],
            'end_time' => 'required|date_format:H:i|after:start_time',
            'maintenance_id' => 'required|exists:maintenances,id',
            'vehicleoccupant_id' => 'required|exists:vehicleoccupants,id',
        ];
    }
}
