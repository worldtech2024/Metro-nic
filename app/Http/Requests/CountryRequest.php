<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
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
            'name_en'      => 'required|string|max:255',
            'name_ar'      => 'required|string|max:255',
            'code'         => 'required|string|max:255',
            'currency'     => 'required|string|max:255',
            'address'      => 'required|string|max:255',
            'phone'        => 'required|string|max:15',
            'mobile'       => 'required|string|max:15',
            'email'        => 'required|string|email|max:255',
            'faxNumber'    => 'required|string|max:15',
            "workWages"    => "required|numeric",
            "generalCost"  => "required|numeric",
            "profitMargin" => "required|numeric",
            "tax"          => "required|numeric",
            "wirePrice"    => "required|numeric",
        ];
    }
}
