<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use App\Imports\BrandsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
//   public function index(Request $request)
//     {
//         $perPage = $request->input('pageNum');
//         $brands = Brand::paginate($perPage);
        
//         return ApiResponse::sendResponse(true, 'Brands retrieved successfully', $brands);
//     }
public function index(Request $request)
{
    $perPage = $request->input('pageNum', 10);
    $query = Brand::query();

    if ($request->filled('name')) {
        $query->where('name', 'LIKE', '%' . $request->name . '%');
    }
    $brands = $query->paginate($perPage);
   
    return ApiResponse::sendResponse(true, 'Brands retrieved successfully', $brands);
}

    public function store(Request $request)
    {
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'discount' => 'required|numeric',
        ]);
        $data['discount'] = (int) $data['discount']; 

        $brand = Brand::create($data);
        
        return ApiResponse::sendResponse(true, 'Brand created successfully', $brand);
    }

    public function update(Request $request, Brand $brand)
    {
       $data= $request->validate([
            'name' => 'sometimes|string|max:255',
            'discount' => 'nullable|numeric',
        ]);
        $brand->update($request->all());
        $brand['discount'] = (int) $data['discount']; 
        return ApiResponse::sendResponse(true, 'Brand updated successfully', $brand);
    }


    public function destroy(Brand $brand)
    {
        $brand->delete();
        return ApiResponse::sendResponse(true, 'Brand deleted successfully');
    }



}