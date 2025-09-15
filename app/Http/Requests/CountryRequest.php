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
    // public function rules(): array
    // {
    //     return [
    //         'name_en'      => 'required|string|max:255',
    //         'name_ar'      => 'required|string|max:255',
    //         'code'         => 'required|string|max:255',
    //         'currency'     => 'required|string|max:255',
    //         'address'      => 'required|string|max:255',
    //         'phone'        => 'required|string|max:15',
    //         'mobile'       => 'required|string|max:15',
    //         'email'        => 'required|string|email|max:255',
    //         'faxNumber'    => 'required|string|max:15',
    //         "workWages"    => "required|numeric",
    //         "generalCost"  => "required|numeric",
    //         "profitMargin" => "required|numeric",
    //         "tax"          => "required|numeric",
    //         "wirePrice"    => "required|numeric",
    //     ];
    // }
    public function rules(): array
     {
        return [
            'name_en'        => 'required|string|regex:/^[A-Za-z\s]+$/|max:255',
            'name_ar'        => 'required|string|regex:/^[\p{Arabic}\s]+$/u|max:255',
            'code'           => 'required|string|max:5',  // مثل KSA, EGY
            'currency'       => 'required|string|alpha|max:10', // مثل SAR, EGP
            'address'        => 'required|string|max:255',
            'phone'          => 'required|regex:/^[0-9]{7,15}$/',
            'mobile'         => 'required|regex:/^[0-9]{7,15}$/',
            'email'          => 'required|string|email|max:255',
            'faxNumber'      => 'required|regex:/^[0-9]{7,15}$/',
            'bankName'       => 'required|string|max:255',
            'IBAN'           => 'required|string|regex:/^SA[0-9]{22}$/', // خاص بالسعودية
            'accountNumber'  => 'required|regex:/^[0-9]{8,20}$/',
            'accountName' => 'required|string|regex:/^[A-Z\s]+$/|max:255',

            "workWages"      => "required|numeric|min:0",
            "generalCost"    => "required|numeric|min:0",
            "profitMargin"   => "required|numeric|min:0|max:100", // نسبة مئوية
            "tax"            => "required|numeric|min:0|max:100", // نسبة مئوية
            "wirePrice"      => "required|numeric|min:0",
            'status'         => 'required|in:active,inactive',
        ];
    }



}
