<?php

namespace App\Http\Requests\ClosingEvent;

use App\Support\ClosingEventAccess;
use Illuminate\Foundation\Http\FormRequest;

class StoreClosingEventRequest extends FormRequest
{
    use ClosingEventRules;

    public function authorize(): bool
    {
        return app(ClosingEventAccess::class)->for($this->user())['canCreate'];
    }

    public function rules(): array
    {
        return $this->closingEventRules();
    }
}
