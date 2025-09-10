<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Trait\ApiResponse;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        return ApiResponse::sendResponse(true, 'countries retrieved successfully', CountryResource::collection($countries));
    }

    public function store(CountryRequest $request)
    {

        $country = Country::create($request->validated());
        return ApiResponse::sendResponse(true, 'Country created successfully', new CountryResource($country));
    }

    public function update(UpdateCountryRequest $request, Country $country)
    {
        $data = $request->validated();
        $country->update($data);
        return ApiResponse::sendResponse(true, 'Country updated successfully', new CountryResource($country));
    }

    public function destroy(Country $country)
    {
        $country->delete();
        return ApiResponse::sendResponse(true, 'Country deleted successfully');
    }
}
