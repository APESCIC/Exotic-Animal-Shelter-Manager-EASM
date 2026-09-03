<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class StoreDietRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string', 'max:5000'],
            'started_on' => ['nullable', 'date'],
            'ended_on' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
