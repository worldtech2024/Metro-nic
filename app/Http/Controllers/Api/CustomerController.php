<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Country;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use App\Exports\CustomerExport;
use App\Trait\ApiFilterPaginate;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\CustomerResource;

class CustomerController extends Controller
{
    use ApiFilterPaginate;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $customers = $this->filterPaginateResource(
            $request,
            User::query()->latest(),
            ['name', 'email', 'phone', 'country_id'],
            ['country'],
            CustomerResource::class,
            10
        );

        return ApiResponse::sendResponse(true, 'Customers retrieved successfully', $customers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        $customer = User::create($request->validated());
        return ApiResponse::sendResponse(true, 'Customer created successfully', new CustomerResource($customer));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return ApiResponse::sendResponse(true, 'Customer retrieved successfully', $user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::where('id', $id)->first();
        $data = $request->validated();
        $user->update($data);
        return ApiResponse::sendResponse(true, 'Customer updated successfully', $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return ApiResponse::sendResponse(true, 'Customer deleted successfully');
    }

    public function import(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
        ]);
        if (! $request->hasFile('file')) {
            return ApiResponse::errorResponse(false, 'لم يتم إرسال أي ملف.');
        }

        $file = $request->file('file');

        if (! $file->isValid()) {
            return ApiResponse::errorResponse(false, 'الملف المرفوع غير صالح.');
        }

        $rows = (new FastExcel)->withoutHeaders()->import($file->getRealPath());

        $headers = $rows[1];

        $dataRows = $rows->slice(2);

        foreach ($dataRows as $rowIndex => $rawRow) {
            $row = [];
            foreach (range(0, count($headers) - 1) as $i) {
                $row[trim($headers[$i])] = $rawRow[$i] ?? null;
            }

            try {
                if (empty($row['email']) || empty($row['name'])) {
                    Log::warning("Missing required user data in row $rowIndex", $row);
                    continue;
                }

                $user = User::where('country_id', $request->input('country_id'))->firstOrNew(['email' => $row['email']]);

                $user->country_id         = $request->input('country_id') ?? null;
                $user->name               = $row['name'];
                $user->phone              = $row['phone'];
                $user->city               = $row['city'] ?? null;
                $user->street             = $row['street'] ?? null;
                $user->neighborhood       = $row['neighborhood'] ?? null;
                $user->zipCode            = $row['zipCode'] ?? null;
                $user->buildingNumber     = $row['buildingNumber'] ?? null;
                $user->additionalNumber   = $row['additionalNumber'] ?? null;
                $user->taxNum             = $row['taxNumber'] ?? null;
                $user->commercialRegister = $row['commercialRegister'] ?? null;

                $user->save();
            } catch (\Exception $e) {
                Log::error('User import error: ' . $e->getMessage(), $row);
            }
        }

        return ApiResponse::sendResponse(true, 'Users imported successfully');
    }

    public function export(Request $request)
    {
        $request->validate([
            'country' => 'required|exists:countries,id',
        ]);
        $country = Country::where('id', $request->input('country'))->pluck('name_en')->first();
        $users = User::where('country_id', $request->input('country'))->get();
        return Excel::download(new CustomerExport($users), 'customers'. '-' . $country . '.xlsx');
    }

}
