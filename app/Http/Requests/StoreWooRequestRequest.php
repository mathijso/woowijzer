<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWooRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isBurger();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $maxSize = config('woo.max_upload_size_mb', 50) * 1024;

        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'document' => "required|file|mimes:pdf|max:{$maxSize}",
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Een titel is verplicht.',
            'title.max' => 'De titel mag maximaal 255 tekens bevatten.',
            'document.required' => 'U moet een PDF document uploaden.',
            'document.file' => 'Het geüploade bestand is geen geldig document.',
            'document.mimes' => 'Het document moet een PDF bestand zijn.',
            'document.max' => 'Het document mag maximaal :max kilobytes groot zijn.',
        ];
    }
}
