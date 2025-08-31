<?php

namespace App\Http\Controllers\Api;

use App\Models\Admin;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function dashboard()
    {
        $today = now()->toDateString();
        $user  = Auth::user();
        $countAdmin = Admin::where('role', 'admin')->count();
        $countPower = Admin::where('role', 'power')->count();
        $countControl = Admin::where('role', 'control')->count();
        $countSales = Admin::where('role', 'sales')->count();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Filter orders by user country
        $ordersQuery = Order::where('country_id', $user->country_id)
            ->whereDate('created_at', $today);

        return response()->json([
            'brandCount'      => Brand::count(),
            'productsCount'   => Product::count(),
            'invoicesToday'   => $ordersQuery->count(),
            'totalSalesToday' => $ordersQuery->sum('totalPrice'),
            'countAdmin'      => $countAdmin,
            'countPower'      => $countPower,
            'countControl'    => $countControl,
            'countSales'      => $countSales,
            'invoicesStatusToday' => [
                'purchased'    => $ordersQuery->clone()->where('status', 'purchased')->count(),
                'notPurchased' => $ordersQuery->clone()->whereIn('status', [
                    'projectCancelled',
                    'createRequest',
                    'negotiationStage',
                    'addRequest',
                    'clientDidNotRespond'
                ])->count(),
                'totalOrder'   => $ordersQuery->count(),
            ],

            'monthlySales' => $this->getMonthlySales($user->country_id),
        ]);
    }

    private function getMonthlySales($countryId)
    {
        $sales = Order::where('country_id', $countryId)
            ->selectRaw('MONTH(created_at) as month, SUM(totalPrice) as total')
            ->whereYear('created_at', now()->year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'month');

        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $result[] = [
                'month' => now()->setMonth($i)->format('M'),
                'total' => round($sales[$i] ?? 0, 2),
            ];
        }

        return [
            'year'        => now()->year,
            'total_sales' => round($sales->sum(), 2),
            'monthly'     => $result,
        ];
    }
}
