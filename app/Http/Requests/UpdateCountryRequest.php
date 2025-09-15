<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCountryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_en'       => 'sometimes|string|max:255',
            'name_ar'       => 'sometimes|string|max:255',
            'code'          => 'sometimes|string|max:255',
            'currency'      => 'sometimes|string|max:255',
            'address'       => 'sometimes|string|max:255',
            'phone'         => 'sometimes|string|max:15',
            'mobile'        => 'sometimes|string|max:15',
            'email'         => 'sometimes|string|email|max:255',
            'bankName'      => 'sometimes|string|max:255',
            'IBAN'          => 'sometimes|string|max:255',
            'accountNumber' => 'sometimes|string|max:255',
            'accountName'   => 'sometimes|string|max:255',
            'faxNumber'     => 'sometimes|string|max:15',
            "workWages"     => "sometimes|numeric",
            "generalCost"   => "sometimes|numeric",
            "profitMargin"  => "sometimes|numeric",
            "tax"           => "sometimes|numeric",
            "wirePrice"     => "sometimes|numeric",
            'status'        => 'sometimes|in:active,inactive',
        ];
    }
}
