<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Order;
use App\Services\MerchantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MerchantController extends Controller
{
    public function __construct(
        MerchantService $merchantService
    ) {}

    /**
     * Useful order statistics for the merchant API.
     *
     * @param Request $request Will include a from and to date
     * @return JsonResponse Should be in the form {
     * count: total number of orders in range,
     * commission_owed: amount of unpaid commissions for orders with an affiliate,
     * revenue: sum order subtotals}
     */
    public function orderStats(Request $request): JsonResponse
    {
        $from = $request->from;
        $to = $request->to;

        $count = Order::whereBetween('created_at', [$from, $to])->count();

        $commissions_owed = Order::has('affiliate')->where('payout_status', 'unpaid')
                            ->where('commission_owed', '!=', '0.00')
                            ->whereBetween('created_at', [$from, $to])
                            ->sum('commission_owed');

        $revenue = Order::where('payout_status', 'unpaid')
            ->where('commission_owed', '!=', '0.00')
            ->whereBetween('created_at', [$from, $to])
            ->sum('subtotal');

        return response()->json([
            "count" => $count,
            "revenue" => $revenue,
            "commissions_owed" => $commissions_owed
        ], 200);

    }
}
