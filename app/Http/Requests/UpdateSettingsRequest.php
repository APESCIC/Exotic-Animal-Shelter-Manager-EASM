<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role->isAdmin() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'organisation_name' => ['required', 'string', 'max:255'],
            'locale' => ['required', 'string', Rule::in(['en_GB', 'en_US'])],
            'timezone' => ['required', 'timezone:all'],
        ];
    }
}
