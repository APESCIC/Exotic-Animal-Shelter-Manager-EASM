<?php

namespace App\Http\Requests;

use App\Enums\MovementType;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = $this->user()?->role;

        return $role instanceof UserRole && $role->canManageMovements();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(MovementType::class)],
            'moved_at' => ['required', 'date'],
            'person_id' => ['nullable', 'integer', 'exists:people,id'],
            'reason' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('person_id') === '' || $this->input('person_id') === null) {
            $this->merge(['person_id' => null]);
        }
    }
}
