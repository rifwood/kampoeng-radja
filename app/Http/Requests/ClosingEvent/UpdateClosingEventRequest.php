<?php

namespace App\Http\Requests\ClosingEvent;

use App\Support\ClosingEventAccess;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClosingEventRequest extends FormRequest
{
    use ClosingEventRules;

    public function authorize(): bool
    {
        return app(ClosingEventAccess::class)->for($this->user())['canUpdate'];
    }

    public function rules(): array
    {
        return [
            ...$this->closingEventRules(),
            'status_event' => ['sometimes', Rule::in(['aktif', 'dibatalkan'])],
            'alasan_pembatalan' => [
                Rule::requiredIf(fn (): bool => $this->input('status_event') === 'dibatalkan'
                    && $this->route('closingEvent')?->status_event !== 'dibatalkan'),
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'alasan_pembatalan.required' => 'Alasan pembatalan wajib diisi saat event dibatalkan.',
        ];
    }
}
