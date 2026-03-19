<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ai_prompt.business_name' => 'sometimes|string|max:255',
            'ai_prompt.business_description' => 'sometimes|string|max:1000',
            'ai_prompt.product_list' => 'sometimes|string',
            'ai_prompt.custom_prompt' => 'sometimes|string|max:2000',

            'channel.whatsapp_phone_number_id' => 'sometimes|string|max:255',
            'channel.whatsapp_access_token' => 'sometimes|string|max:500',
            'channel.whatsapp_verify_token' => 'sometimes|string|max:255',
            'channel.whatsapp_app_secret' => 'sometimes|string|max:255',

            'channel.instagram_access_token' => 'sometimes|string|max:500',
            'channel.instagram_verify_token' => 'sometimes|string|max:255',
            'channel.instagram_app_secret' => 'sometimes|string|max:255',

            'general.anthropic_api_key' => 'sometimes|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'ai_prompt.business_name.max' => 'Nama toko maksimal 255 karakter',
            'ai_prompt.business_description.max' => 'Deskripsi maksimal 1000 karakter',
            'ai_prompt.custom_prompt.max' => 'Custom prompt maksimal 2000 karakter',
            'channel.whatsapp_access_token.max' => 'WhatsApp access token maksimal 500 karakter',
            'channel.instagram_access_token.max' => 'Instagram access token maksimal 500 karakter',
        ];
    }
}
