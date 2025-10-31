<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // This is used in the public upload portal, no authentication required
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $maxSize = config('woo.max_upload_size_mb', 50) * 1024;
        $allowedTypes = array_keys(config('woo.allowed_file_types', []));
        $mimes = implode(',', $allowedTypes);

        return [
            'submitted_by_name' => 'nullable|string|max:255',
            'submission_notes' => 'nullable|string|max:2000',
            'documents' => 'required|array|min:1|max:20',
            'documents.*' => "required|file|mimes:{$mimes}|max:{$maxSize}",
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'submitted_by_name.max' => 'Uw naam mag maximaal 255 tekens bevatten.',
            'submission_notes.max' => 'Notities mogen maximaal 2000 tekens bevatten.',
            'documents.required' => 'U moet minimaal één document uploaden.',
            'documents.array' => 'De documenten moeten als een lijst worden aangeleverd.',
            'documents.min' => 'U moet minimaal één document uploaden.',
            'documents.max' => 'U kunt maximaal 20 documenten tegelijk uploaden.',
            'documents.*.required' => 'Elk document is verplicht.',
            'documents.*.file' => 'Elk bestand moet een geldig document zijn.',
            'documents.*.mimes' => 'Alleen de volgende bestandstypen zijn toegestaan: PDF, Word, afbeeldingen.',
            'documents.*.max' => 'Elk document mag maximaal :max kilobytes groot zijn.',
        ];
    }
}
