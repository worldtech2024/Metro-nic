<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
   public function index()
   {
      $countries = Country::all();
      return ApiResponse::sendResponse(true, 'countries retrieved successfully', $countries);
   }

   public function store(Request $request)
   {
      $country = Country::create($request->all());
      return ApiResponse::sendResponse(true, 'Country created successfully', $country);
   }

   public function update(Request $request, Country $country)
   {
    $data = $request->validate([
        'name_en' => 'sometimes|string|max:255',
        'name_ar' => 'sometimes|string|max:255',
        'code' => 'sometimes|string|max:255',
        'currency' => 'sometimes|string|max:255',
    ]);
      $country->update($data);
      return ApiResponse::sendResponse(true, 'Country updated successfully', $country);
   }

   public function destroy(Country $country)
   {
      $country->delete();
      return ApiResponse::sendResponse(true, 'Country deleted successfully');
   }


}
