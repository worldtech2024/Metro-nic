<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailsOrderResource;
use App\Http\Resources\OrderResource;
use App\Models\Busbare;
use App\Models\Order;
use App\Models\OrderUnit;
use App\Models\ProductUnit;
use App\Models\User;
use App\Notifications\SalesCreateOrderNotification;
use App\Notifications\UpdateOrderNotification;
use App\Trait\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use \App\Trait\ApiFilterPaginate;

    public function show(Order $order)
    {
        $order = Order::with([
            'user', 'admin', 'orderUnits.productUnits', 'orderUnits.busbares', 'admin_buy', 'admin_install', 'admin.country', 'user.country', 'admin_buy.country', 'admin_install.country',
        ])->find($order->id);
        // dd($order->orderUnits);

        return ApiResponse::sendResponse(true, 'Order retrieved successfully', DetailsOrderResource::make($order));
    }
    public function index(Request $request)
    {

        $country = Auth::user()->country_id; // get country of admin login using token
        $type    = Auth::user()->role;       // get role of admin login using token

        if ($type == 'power') {
            $orders = $this->filterPaginateResource(
                $request,
                Order::query()->where('send', 'power')->where('country_id', $country)->latest(),
                // give all orders where send is power and country is admin country
                ['projectName', 'status'],
                // search projectName and status
                [],
                OrderResource::class,
                10
            );
        } elseif ($type == 'control') {
            $orders = $this->filterPaginateResource(
                $request,
                Order::query()->where('send', 'control')->where('country_id', $country)->latest(),
                // give all orders where send is control and country is admin country
                ['projectName', 'status'],
                [],
                OrderResource::class,
                10
            );
        } else {
            $orders = $this->filterPaginateResource(
                $request,
                Order::query()->where('country_id', $country)->latest(),
                // give all orders where country is admin country
                ['projectName', 'status'],
                [],
                OrderResource::class,
                10
            );
        }

        return ApiResponse::sendResponse(true, 'Orders Retrieved Successfully', $orders);
    }

    public function myOrders(Request $request)
    {
        $user = Auth::user();

        $orders = $this->filterPaginateResource(
            $request,
            Order::query()->where('admin_id', $user->id)->latest(),
            ['projectName', 'status'],
            // search projectName and status
            [],
            OrderResource::class,
            10
        );

        return ApiResponse::sendResponse(true, 'Orders Retrieved Successfully', $orders);
    }

    public function myOderBuy(Request $request)
    {
        $user   = Auth::user();
        $name   = $request->query('name');
        $orders = $this->filterPaginateResourceForEmployee(
            $request,
            Order::query()->where('admin_buy_id', $user->id)->where('status', 'sendPurchase')->latest(),
            // ['user', 'admin', 'orderUnits.productUnits', 'orderUnits.busbares', 'admin_buy', 'admin_install', 'admin.country', 'user.country'],
            [],
            OrderResource::class,
            10,
            $user->id
        );

        return ApiResponse::sendResponse(true, 'Orders Retrieved Successfully', $orders);
    }

    public function myOderInstall(Request $request)
    {
        $user   = Auth::user();
        $name   = $request->query('name');
        $orders = $this->filterPaginateResourceForEmployee(
            $request,
            Order::query()->where('admin_install_id', $user->id)->where('status', 'sendInstall')->latest(),
            [],
            // ['user', 'admin', 'orderUnits.productUnits', 'orderUnits.busbares', 'admin_buy', 'admin_install', 'admin.country', 'user.country', 'admin_buy.country', 'admin_install.country'],
            OrderResource::class,
            10,
            $user->id
        );
        return ApiResponse::sendResponse(true, 'Orders Retrieved Successfully', $orders);
    }

    public function myCompletedOrders(Request $request)
    {
        $user  = Auth::user();
        $query = Order::query()
            ->where(function ($q) use ($user) {
                $q->where('admin_buy_id', $user->id)
                    ->orWhere('admin_install_id', $user->id);
            })
            ->where(function ($q) {
                $q->where('status', 'purchased')
                    ->orWhere('status', 'installed');
            })
            ->with([])
            ->latest();

        if ($request->filled('name')) {
            $query->where('projectName', 'LIKE', '%' . $request->query('name') . '%');
        }

        $perPage   = (int) $request->input('pageNum', 10);
        $paginated = $query->paginate($perPage);

        $paginated->getCollection()->transform(function ($item) {
            return new OrderResource($item);
        });

        return ApiResponse::sendResponse(true, 'Orders Retrieved Successfully', $paginated);
    }

    // public function myOrders(Request $request)
    // {
    //     $user = Auth::user();

    //     $type = $request->query('type');
    //     $query = Order::query();

    //     if ($type === 'buy') {
    //         $query->where('admin_buy_id', $user->id)
    //             ->where('status', 'sendPurchase');
    //     } elseif ($type === 'install') {
    //         $query->where('admin_install_id', $user->id)
    //             ->where('status', 'sendInstall');
    //     } elseif ($type === 'completed') {
    //         $query->where(function ($q) use ($user) {
    //             $q->where('admin_buy_id', $user->id)
    //                 ->whereIn('status', ['purchased', 'sendInstall', 'installed']);
    //         })->orWhere(function ($q) use ($user) {
    //             $q->where('admin_install_id', $user->id)
    //                 ->whereIn('status', ['sendInstall', 'installed']);
    //         });
    //     } else {
    //         return ApiResponse::sendResponse(false, 'Invalid type parameter');
    //     }

    //     $orders = $this->filterPaginateResourceForEmployee(
    //         $request,
    //         $query,
    //         ['projectName', 'status'],
    //         ['user', 'admin'],
    //         OrderResource::class,
    //         10,
    //         $user->id
    //     );

    //     return ApiResponse::sendResponse(true, 'Orders Retrieved Successfully', $orders);
    // }

    public function store(Request $request)
    {
        $order = $request->validate([
            'user_id'     => 'required|exists:users,id',
            'projectName' => 'required|string',
            'description' => 'nullable|string',
            'deadline'    => 'required|date',
            'send'        => 'required|in:power,control',
        ]);

        $order['deadline'] = Carbon::parse($request->deadline)->format('Y-m-d');

        $order['admin_id']    = Auth::user()->id;
        $order['orderNumber'] = rand(1000000000, 9999999999);
        $order['description'] = $request->description;
        $order['country_id']  = User::find($order['user_id'])->country_id;
        $order                = Order::create($order);
        $order['description'] = $request->description ?? '';
        return ApiResponse::sendResponse(true, 'Order created successfully', $order);
    }

    public function addInvoice(Request $request)
    {
        $request->validate([
            'order_id'                                 => 'required|exists:orders,id',
            'status'                                   => 'required|in:addRequest,negotiationStage,sendPurchase,purchased,sendInstall,installed,clientDidNotRespond,projectCancelled',
            'customerFileNumber'                       => 'required|integer',
            'order_units'                              => 'nullable|array',

            'order_units.*.name'                       => 'nullable|string',
            'order_units.*.product_units'              => 'nullable|array',
            'order_units.*.product_units.*.product_id' => 'nullable|exists:products,id',
            'order_units.*.product_units.*.quantity'   => 'nullable|integer|min:1',

            'order_units.*.busbars'                    => 'nullable|array',
            'order_units.*.busbars.*.amp'              => 'nullable|numeric',
            'order_units.*.busbars.*.pl'               => 'nullable|numeric',
            'order_units.*.busbars.*.up'               => 'nullable|numeric',
            'order_units.*.busbars.*.qty'              => 'nullable|integer|min:1',
            'order_units.*.busbars.*.priceForMeter'    => 'nullable|numeric',

            'order_units.*.generalCost'                => 'nullable|numeric',
            'order_units.*.generalCostPercentage'      => 'nullable|numeric',
            'order_units.*.workWages'                  => 'nullable|numeric',
            'order_units.*.workWagesPercentage'        => 'nullable|numeric',
            'order_units.*.profitMargin'               => 'nullable|numeric',
            'order_units.*.profitMarginPercentage'     => 'nullable|numeric',
            'order_units.*.vat'                        => 'nullable|numeric',
            'order_units.*.vatPercentage'              => 'nullable|numeric',
            'order_units.*.brandDiscount'              => 'nullable|numeric',
            'order_units.*.finalDiscount'              => 'nullable|numeric',
            'order_units.*.totalPrice'                 => 'nullable|numeric',
            'order_units.*.totalBusbar'                => 'nullable|numeric',
            'order_units.*.subTotal'                   => 'nullable|numeric',
            'order_units.*.notes'                      => 'nullable|string',

        ]);

        $order = Order::findOrFail($request->order_id);

        if (OrderUnit::where('order_id', $order->id)->exists()) {
            foreach ($order->orderUnits as $unit) {
                $unit->delete();
            }
            // return ApiResponse::errorResponse(false, 'Invoice already exists for this order');
        }

        DB::transaction(function () use ($request, $order) {
            foreach ($request->order_units as $unitData) {
                // dd($unitData);
                $orderUnit = OrderUnit::create([
                    'order_id'               => $order->id,
                    'name'                   => $unitData['name'],
                    'subTotal'               => $unitData['subTotal'] ?? 0,
                    'brandDiscount'          => $unitData['brandDiscount'] ?? 0,
                    'finalDiscount'          => $unitData['finalDiscount'] ?? 0,
                    'totalPrice'             => $unitData['totalPrice'] ?? 0,
                    'totalBusbar'            => $unitData['totalBusbar'] ?? 0,
                    'generalCost'            => $unitData['generalCost'],
                    'generalCostPercentage'  => $unitData['generalCostPercentage'],
                    'workWages'              => $unitData['workWages'],
                    'workWagesPercentage'    => $unitData['workWagesPercentage'],
                    'profitMargin'           => $unitData['profitMargin'],
                    'profitMarginPercentage' => $unitData['profitMarginPercentage'],
                    'vat'                    => $unitData['vat'],
                    'vatPercentage'          => $unitData['vatPercentage'],
                    'notes'                  => $unitData['notes'] ?? null,

                ]);

                foreach ($unitData['product_units'] as $productData) {
                    ProductUnit::create([
                        'order_unit_id' => $orderUnit->id,
                        'product_id'    => $productData['product_id'],
                        'quantity'      => $productData['quantity'],
                    ]);
                }

                if (! empty($unitData['busbars'])) {
                    foreach ($unitData['busbars'] as $busbarData) {
                        Busbare::create([
                            'order_unit_id' => $orderUnit->id,
                            'order_id'      => $order->id,
                            'amp'           => $busbarData['amp'],
                            'PL'            => $busbarData['pl'] ?? null,
                            'UP'            => $busbarData['up'],
                            'quantity'      => $busbarData['qty'],
                            'priceForMeter' => $busbarData['priceForMeter'],
                        ]);
                    }
                }
            }

            $subTotal      = $order->orderUnits->sum('subTotal');
            $totalBusbar   = $order->orderUnits->sum('totalBusbar');
            $discountTotal = $order->orderUnits->sum('brandDiscount');
            $totalVat      = $order->orderUnits->sum('vat') + $order->orderUnits->sum('generalCost') + $order->orderUnits->sum('workWages') + $order->orderUnits->sum('profitMargin');
            $totalPrice    = $order->orderUnits()->sum('totalPrice');

            if (! $order->sendNotification) {
                $order->admin->notify(new SalesCreateOrderNotification($order));

                $order->update([
                    'sendNotification' => true,
                ]);
            }

            $order->update([
                'subTotal'           => $subTotal,
                'totalBusbar'        => $totalBusbar,
                'DiscountTotal'      => $discountTotal,
                'totalPrice'         => $totalPrice,
                'totalVAT'           => $totalVat,
                'CustomerFileNumber' => $request->customerFileNumber ?? null,
                'status'             => $request->status,
            ]);
        });

        $order = Order::with([
            'user',
            'admin',
            'admin.country',
            'admin_buy.country',
            'admin_install.country',
            'user.country',
            'orderUnits.productUnits',
            'orderUnits.busbares',
            'admin_buy',
            'admin_install',
        ])->find($order->id);

        return ApiResponse::sendResponse(true, 'Invoice added successfully', DetailsOrderResource::make($order));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status'           => 'required|in:negotiationStage,sendPurchase,purchased,sendInstall,installed,clientDidNotRespond,projectCancelled',
            'admin_buy_id'     => 'required_if:status,sendPurchase|exists:admins,id',
            'admin_install_id' => 'required_if:status,sendInstall|exists:admins,id',

        ]);
        if (
            $data['status'] === 'sendInstall'
            && ! in_array($order->status, ['sendPurchase', 'purchased'])
        ) {
            return ApiResponse::errorResponse(false, 'You cannot assign installation before purchase is sent or completed');
        }

        if ($request->status == "negotiationStage") {
            $data['admin_buy_id']     = null;
            $data['admin_install_id'] = null;
        } elseif ($request->status == "purchased") {
            $data['admin_install_id'] = null;
        }

        $data['verify'] = true;

        $order->update($data);

        $order = Order::with([
            'user',
            'admin',
            'admin.country',
            'admin_buy.country',
            'admin_install.country',
            'user.country',
            'orderUnits.productUnits',
            'orderUnits.busbares',
            'admin_buy',
            'admin_install',
        ])->find($order->id);

        if (! empty($data['admin_buy_id']) && $order->admin_buy) {
            $order->admin_buy->notify(new UpdateOrderNotification($order, 'buy'));
        }

        if (! empty($data['admin_install_id']) && $order->admin_install) {
            $order->admin_install->notify(new UpdateOrderNotification($order, 'install'));
        }

        return ApiResponse::sendResponse(true, 'Order updated successfully', DetailsOrderResource::make($order));
    }

    // 'status' => [
    //     Rule::in([
    //         'createRequest',// انشاء طلب
    //         'addRequest',// اضافة طلب
    //         'negotiationStage',// مراحل التفاوض
    //         'sendPurchase', // ارسال الشراء
    //         'purchased', // تم الشراء
    //         'sendInstall', // ارسال التثبيت
    //         'installed', // تم التثبيت
    //         'clientDidNotRespond', // لم يرد العميل
    //         'projectCancelled' // تم الغاء المشروع
    //     ])

    // ],
}
