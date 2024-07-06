<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ActivityRequest extends FormRequest
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
        'horary_id' => 'required|exists:horaries,id',
        'date' => [
            'required',
            'date',
            function ($attribute, $value, $fail) {
                $horary = \App\Models\Horary::find($this->horary_id);
                if ($horary) {
                    $dayOfWeek = \Carbon\Carbon::parse($value)->format('l');
                    $days = [
                        'Monday' => 'Lunes',
                        'Tuesday' => 'Martes',
                        'Wednesday' => 'Miércoles',
                        'Thursday' => 'Jueves',
                        'Friday' => 'Viernes',
                        'Saturday' => 'Sábado',
                        'Sunday' => 'Domingo',
                    ];
                    if ($days[$dayOfWeek] !== $horary->day) {
                        $fail('La fecha de la actividad no coincide con el día del horario.');
                    }

                    $maintenance = \App\Models\Maintenance::find($horary->maintenance_id);
                    if ($maintenance) {
                        $activityDate = \Carbon\Carbon::parse($value);
                        $initialDate = \Carbon\Carbon::parse($maintenance->initial_date);
                        $finalDate = \Carbon\Carbon::parse($maintenance->final_date);
                        if ($activityDate->lt($initialDate) || $activityDate->gt($finalDate)) {
                            $fail('La fecha de la actividad no está dentro del rango permitido del mantenimiento.');
                        }
                    }
                }
            },
        ],
        'description' => 'required|string|max:255',
        ];
    }
}
