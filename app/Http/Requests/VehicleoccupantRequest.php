<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Vehicleoccupant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VehicleoccupantRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if ($user) {
                        // Validar que solo un chofer puede ser asignado a un vehículo y que siempre haya al menos un chofer
                        if ($user->typeuser->name === 'Chofer/Conductor') {
                            $existingDriver = Vehicleoccupant::where('vehicle_id', $this->vehicle_id)
                                ->whereHas('user.typeuser', function ($query) {
                                    $query->where('name', 'Chofer/Conductor');
                                })
                                ->first();
                            if ($existingDriver) {
                                $fail('Ya existe un chofer asignado a este vehículo.');
                            }
                        } else {
                            // Asegurar que hay al menos un chofer si se intenta agregar un reciclador
                            $driverCount = Vehicleoccupant::where('vehicle_id', $this->vehicle_id)
                                ->whereHas('user.typeuser', function ($query) {
                                    $query->where('name', 'Chofer/Conductor');
                                })
                                ->count();
                            if ($driverCount === 0) {
                                $fail('Debe haber al menos un chofer en el vehículo antes de agregar recicladores.');
                            }
                        }

                        // Validar que un reciclador no puede ser asignado dos veces al mismo vehículo
                        if ($user->typeuser->name === 'Reciclador') {
                            $existingRecycler = Vehicleoccupant::where('vehicle_id', $this->vehicle_id)
                                ->where('user_id', $value)
                                ->first();
                            if ($existingRecycler) {
                                $fail('Este reciclador ya está asignado a este vehículo.');
                            }
                        }

                        // Validar que un chofer o reciclador no puede estar en dos vehículos
                        $userInOtherVehicle = Vehicleoccupant::where('user_id', $value)
                            ->where('vehicle_id', '!=', $this->vehicle_id)
                            ->first();
                        if ($userInOtherVehicle) {
                            $fail('Este usuario ya está asignado a otro vehículo. Vehículo: ' . $userInOtherVehicle->vehicle->name);
                        }
                    }
                },
            ],
            'vehicle_id' => [
                'required',
                'exists:vehicles,id',
                function ($attribute, $value, $fail) {
                    $vehicle = Vehicle::find($value);
                    if ($vehicle) {
                        $currentOccupants = Vehicleoccupant::where('vehicle_id', $value)->count();
                        if ($currentOccupants >= $vehicle->capacity) {
                            $fail('La capacidad máxima del vehículo ha sido alcanzada.');
                        }
                    }
                },
            ],
        ];
    }
}
