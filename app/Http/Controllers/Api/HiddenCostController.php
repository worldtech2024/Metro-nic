<?php

namespace App\Http\Controllers\Api;

use App\Models\HiddenCost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Trait\ApiResponse;

class HiddenCostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hiddenCosts = HiddenCost::get();
        return ApiResponse::sendResponse(true, 'Hidden costs retrieved successfully', $hiddenCosts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

            "workWages" => 'required|numeric', //  اجور العمالة
            "generalCost" => 'required|numeric',// التكاليف العامة
            "profitMargin" => 'required|numeric',// هامش الربح
            "tax" => 'required|numeric', // الضريبة
            "wirePrice" => 'required|numeric', // سعر السلك


        ]);

        $hiddenCost = HiddenCost::create($request->all());

        return ApiResponse::sendResponse(true, 'Hidden cost created successfully', $hiddenCost);
    }

    /**
     * Display the specified resource.
     */
    public function show(HiddenCost $hiddenCost)
    {
        return ApiResponse::sendResponse(true, 'Hidden cost retrieved successfully', $hiddenCost);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HiddenCost $hiddenCost)
    {
        $data = $request->validate([
            "workWages" => 'sometimes|required|numeric',
            "generalCost" => 'sometimes|required|numeric',
            "profitMargin" => 'sometimes|required|numeric',
            "tax" => 'sometimes|required|numeric',
            "wirePrice" => 'sometimes|required|numeric',
        ]);

        $hiddenCost->update($data);

        $hiddenCost->workWages = (int) $hiddenCost->workWages;
        $hiddenCost->generalCost = (int) $hiddenCost->generalCost;
        $hiddenCost->profitMargin = (int) $hiddenCost->profitMargin;
        $hiddenCost->tax = (int) $hiddenCost->tax;
        $hiddenCost->wirePrice = (int) $hiddenCost->wirePrice;

        return ApiResponse::sendResponse(true, 'Hidden cost updated successfully', $hiddenCost);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HiddenCost $hiddenCost)
    {
        $hiddenCost->delete();
        return ApiResponse::sendResponse(true, 'Hidden cost deleted successfully');
    }
}