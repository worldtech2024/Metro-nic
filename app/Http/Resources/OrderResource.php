<?php
namespace App\Http\Resources;

use App\Http\Resources\AdminResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\OrderUnitResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = Auth::user();
        return [
            'id'                   => $this->id,
            'orderNumber'          => $this->orderNumber,
            'projectName'          => $this->projectName,
            // Flags
            'isPurchaseDone'       => $this->admin_buy_id === $user->id
            ? in_array($this->status, ['purchased', 'installed'])
            : false,

            'isInstallationDone'   => $this->admin_install_id === $user->id
            ? $this->status === 'installed'
            : false,
            'customer'             => $this->user->name,
            'admin'             => $this->admin->name,
            // 'customerFileNumber'   => $this->CustomerFileNumber ?: '',
            'send'                 => $this->send,
            // 'description'          => $this->description ?: '',
            'status'               => $this->status,
            'deadline'             => $this->deadline,
            // 'subtotal'             => $this->subTotal,
            // 'totalBusbar'          => $this->totalBusbar,
            // 'totalDiscount'        => $this->DiscountTotal,
            // 'totalVat'             => $this->totalVAT,
            // 'totalPrice'           => $this->totalPrice,
            'created_at'           => Carbon::parse($this->created_at)->format('Y-m-d'),

            // 'updated_at'           => Carbon::parse($this->updated_at)->format('Y-m-d'),
            // 'country'              => CountryResource::make($this->country) ?? null,
            // 'admin'                => AdminResource::make($this->admin) ?? null,
            // 'purchasingOfficer'    => AdminResource::make($this->admin_buy) ?? null,
            // 'installationEmployee' => AdminResource::make($this->admin_install) ?? null,
            // 'customer'             => new CustomerResource($this->user) ?? null,
            // 'order_units'          => OrderUnitResource::collection($this->orderUnits) ?? null,
        ];
    }
}
