<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class StoreAnimalObservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = $this->user()?->role;

        return $role instanceof UserRole && $role->canManageMedical();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'observed_on' => ['required', 'date'],
            'body' => ['required', 'string', 'max:5000'],
        ];
    }
}
