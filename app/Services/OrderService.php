<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;

class OrderService
{
    public function __construct(
        protected AffiliateService $affiliateService
    ) {}

    /**
     * Process an order and log any commissions.
     * This should create a new affiliate if the customer_email is not already associated with one.
     * This method should also ignore duplicates based on order_id.
     *
     * @param  array{
     * order_id: string,
     * subtotal_price: float,
     * merchant_domain: string,
     * discount_code: string,
     * customer_email: string,
     * customer_name: string
     * } $data
     * @return void
     */
    public function processOrder(array $data)
    {
        $merchant = Merchant::with('user')
                    ->where('domain', $data['merchant_domain'])
                    ->first();
        $user = $merchant->user;

        $affiliate = $this->affiliateService->register(
            $merchant,
            $data['customer_email'],
            $data['customer_name'],
            0.1
        );

        $order = new Order;
        $order->merchant_id = $merchant->id;
        $order->affiliate_id = $affiliate->id;
        $order->subtotal = $data['subtotal_price'];
        $order->commission_owed = ($data['subtotal_price'] * $affiliate->commission_rate);
        $order->discount_code = $data['discount_code'];
        $order->save();

        return $order;
    }
}
