<?php

namespace App\Http\Requests;

use App\Enums\LostFoundType;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLostFoundReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = $this->user()?->role;

        return $role instanceof UserRole && $role->canManageLostFound();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(LostFoundType::class)],
            'species' => ['required', 'string', 'max:255'],
            'colour' => ['nullable', 'string', 'max:255'],
            'markings' => ['nullable', 'string', 'max:255'],
            'identifying_code' => ['nullable', 'string', 'max:255'],
            'location_area' => ['nullable', 'string', 'max:255'],
            'reported_at' => ['required', 'date'],
            'person_id' => ['nullable', 'integer', 'exists:people,id'],
            'matched_animal_id' => ['nullable', 'integer', 'exists:animals,id'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
