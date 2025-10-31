<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInternalRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isCaseManager();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'woo_request_id' => 'required|exists:woo_requests,id',
            'colleague_email' => 'required|email|max:255',
            'colleague_name' => 'nullable|string|max:255',
            'description' => 'required|string|max:5000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'woo_request_id.required' => 'Een WOO verzoek is verplicht.',
            'woo_request_id.exists' => 'Het geselecteerde WOO verzoek bestaat niet.',
            'colleague_email.required' => 'Het emailadres van de collega is verplicht.',
            'colleague_email.email' => 'Voer een geldig emailadres in.',
            'colleague_name.max' => 'De naam mag maximaal 255 tekens bevatten.',
            'description.required' => 'Een toelichting is verplicht.',
            'description.max' => 'De toelichting mag maximaal 5000 tekens bevatten.',
        ];
    }
}
