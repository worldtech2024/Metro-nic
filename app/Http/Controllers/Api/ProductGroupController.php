<?php

namespace App\Http\Controllers\Api;

use App\Trait\ApiResponse;
use App\Models\ProductGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
{
    $perPage = $request->input('pageNum', 10); // default 10

    $query = ProductGroup::query();

    if ($request->filled('name')) {
        $query->where('name', 'LIKE', '%' . $request->name . '%');
    }

    $productGroups = $query->paginate($perPage);

    return ApiResponse::sendResponse(true, 'Product groups retrieved successfully', $productGroups);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $productGroup = ProductGroup::create($request->all());

        return ApiResponse::sendResponse(true, 'Product group created successfully', $productGroup);

    }

    /**
     * Display the specified resource.
     */
    public function show(ProductGroup $productGroup)
    {
        return ApiResponse::sendResponse(true, 'Product group retrieved successfully', $productGroup);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductGroup $productGroup)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $productGroup->update($request->all());

        return ApiResponse::sendResponse(true, 'Product group updated successfully', $productGroup);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductGroup $productGroup)
    {
        $productGroup->delete();
        return ApiResponse::sendResponse(true, 'Product group deleted successfully');
    }
}