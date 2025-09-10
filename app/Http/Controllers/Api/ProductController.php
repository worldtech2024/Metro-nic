<?php
namespace App\Http\Controllers\Api;

use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Product;
use App\Trait\ApiFilterPaginate;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductController extends Controller
{
    use ApiFilterPaginate;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $products = $this->filterPaginateResource(
            $request,
            Product::query()->latest(),
            ['name', 'productNum', 'brand'],
            ['brand'],
            ProductResource::class,
            10
        );

        return ApiResponse::sendResponse(true, 'Products retrieved successfully', $products);

        // $perPage = $request->input('pageNum', 10); // default to 10 if not provided

        // $query = Product::query();

        // if ($request->filled('name')) {
        //     $query->where('products.name', 'LIKE', '%' . $request->name . '%');
        // }

        // $products = $query
        //     ->join('brands', 'products.brand_id', '=', 'brands.id')
        //     ->select(
        //         'products.id',
        //         'products.name',
        //         'products.productNum',
        //         'products.sellingPrice',
        //         'brands.id as brand_id',
        //         'brands.name as brand_name',
        //         'brands.discount',

        //     )
        //     ->paginate($perPage);

        // return ApiResponse::sendResponse(true, 'Products retrieved successfully', $products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand_id'     => 'required|exists:brands,id',
            "name"         => 'required|string|max:255',
            "productNum"   => 'required',
            "sellingPrice" => 'required|integer',
        ]);

        $product = Product::create($request->all());
        return ApiResponse::sendResponse(true, 'Product created successfully', $product);
    }

    /**
     * Display the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // dd($product->productNum);
        $request->validate([
            'brand_id'     => 'sometimes|exists:brands,id',
            "name"         => 'sometimes|string|max:255',
            // "productNum" => 'sometimes|integer|unique:products,productNum',
            "productNum"   => 'sometimes|unique:products,productNum,', $product->productNum,
            "sellingPrice" => 'sometimes|integer',
        ]);

        $product->update($request->all());
        return ApiResponse::sendResponse(true, 'Product updated successfully', $product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return ApiResponse::sendResponse(true, 'Product deleted successfully');
    }
    /**
     * Import products from an Excel file.
     */

    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required'
    //     ]);
    //     $file = $request->file('file');

    //     $rows = (new FastExcel)->withoutHeaders()->import($file);

    //     $headers = $rows[1];

    //     $dataRows = $rows->slice(2)->take(1000000);

    //     foreach ($dataRows as $rowIndex => $rawRow) {
    //         $row = [];
    //         foreach (range(0, 7) as $i) {
    //             $row[trim($headers[$i])] = $rawRow[$i] ?? null;
    //         }

    //         try {
    //             if (empty($row['brand_name']) || empty($row['item_number'])) {
    //                 Log::warning("Missing required data in row $rowIndex", $row);
    //                 continue;
    //             }

    //             $brand = Brand::firstOrCreate(
    //                 ['name' => trim($row['brand_name'])],
    //                 ['status' => 'active']
    //             );

    //             $product = Product::firstOrNew(['productNum' => $row['item_number']]);
    //             $product->brand_id = $brand->id;
    //             $product->name = $row['item_name'] ?? 'Unnamed';
    //             $product->sellingPrice = floatval(str_replace(',', '', $row['unit_price'] ?? 0));
    //             $product->save();
    //         } catch (\Exception $e) {
    //             Log::error('Import error: ' . $e->getMessage(), $row);
    //         }
    //     }

    //     return ApiResponse::sendResponse(true, 'Products imported successfully');
    // }

    // public function import(Request $request)
    // {

    //     $request->validate([
    //         'file' => 'required',
    //     ]);
    //     $file = $request->file('file');

    //     $rows = (new FastExcel)->withoutHeaders()->import($file);

    //     $headers = isset($rows[1]) ? $rows[1] : [];

    //     $dataRows = $rows->slice(2)->take(1000000);

    //     foreach ($dataRows as $rowIndex => $rawRow) {
    //         $row = [];

    //         foreach ($headers as $i => $headerName) {
    //             $row[trim($headerName)] = $rawRow[$i] ?? null;
    //         }

    //         try {
    //             if (empty($row['brand_name']) || empty($row['item_number'])) {
    //                 Log::warning("Missing required data in row $rowIndex", $row);
    //                 continue;
    //             }
    //             $brand = Brand::where('name', 'like', "%" . trim($row['brand_name']) . "%")->first();
    //             $brand = Brand::firstOrCreate(
    //                 ['name' => trim($row['brand_name'])],
    //                 ['discount' => 0]
    //             );
    //             // $product = Product::updateOrCreate(['productNum' => $row['item_number']]);
    //             // $product->brand_id = $brand->id;
    //             // $product->name = $row['item_name'] ?? 'Unnamed';
    //             // $product->sellingPrice = floatval(str_replace(',', '', $row['unit_price'] ?? 0));
    //             // $product->save();
    //             $product = Product::updateOrCreate(
    //                 ['productNum' => $row['item_number']], // condition
    //                 [                                      // values to update/create
    //                     'brand_id'     => $brand->id,
    //                     'name'         => $row['item_name'] ?? 'Unnamed',
    //                     'sellingPrice' => floatval(str_replace(',', '', $row['unit_price'] ?? 0)),
    //                 ]
    //             );
    //         } catch (\Exception $e) {
    //             Log::error('Import error: ' . $e->getMessage(), $row);
    //         }
    //     }

    //     return ApiResponse::sendResponse(true, 'Products imported successfully');
    // }
public function import(Request $request)
{
    $request->validate([
        'file' => 'required'
    ]);

    $file = $request->file('file');
    $rows = (new FastExcel)->withoutHeaders()->import($file);

    $headers = isset($rows[1]) ? $rows[1] : [];
    $dataRows = $rows->slice(2)->take(1000000);

    foreach ($dataRows as $rowIndex => $rawRow) {
        $row = [];

        foreach ($headers as $i => $headerName) {
            $row[trim($headerName)] = $rawRow[$i] ?? null;
        }

        try {
            if (empty($row['brand_name']) || empty($row['item_number'])) {
                Log::warning("Missing required data in row $rowIndex", $row);
                continue;
            }

            $brandName = trim($row['brand_name']);

            // 🔍 Check if brand exists exactly
            $exactBrand = Brand::where('name', $brandName)->first();

            if ($exactBrand) {
                $brand = $exactBrand;
            } else {
                // 🔍 Check if any similar brand exists
                $likeBrand = Brand::where('name', 'like', '%' . $brandName . '%')->first();

                if ($likeBrand) {
                    // 👌 Skip create, use the found similar brand
                    $brand = $likeBrand;
                } else {
                    // ✅ Create new brand only if not found (exact or like)
                    $brand = Brand::create([
                        'name'     => $brandName,
                        'discount' => 0
                    ]);
                }
            }

            // 🔍 Update or create product using productNum
            $product = Product::updateOrCreate(
                ['productNum' => $row['item_number']], // condition
                [ // values
                    'brand_id'      => $brand->id,
                    'name'          => $row['item_name'] ?? 'Unnamed',
                    'sellingPrice'  => floatval(str_replace(',', '', $row['unit_price'] ?? 0)),
                ]
            );

        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage(), $row);
        }
    }

    return ApiResponse::sendResponse(true, 'Products imported successfully');
}

    public function export()
    {
        // dd('asd');
        // $products = Product::all();
        return Excel::download(new ProductExport(), 'products' . '.xlsx');
    }

}
