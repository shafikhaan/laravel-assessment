<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\User;
use App\Models\Order;
use App\Models\Merchant;
use App\Jobs\PayoutOrderJob;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * You don't need to do anything here. This is just to help
 */
class ApiService
{
    /**
     * Create a new discount code for an affiliate
     *
     * @param Merchant $merchant
     *
     * @return array{id: int, code: string}
     */
    public function createDiscountCode(Merchant $merchant): array
    {
        return [
            'id' => rand(0, 100000),
            'code' => Str::uuid()
        ];
    }

    /**
     * Send a payout to an email
     *
     * @param  string $email
     * @param  float $amount
     * @return void
    //  * @throws RuntimeException
     */
    public function sendPayout(string $email, float $amount)
    {
        try {
            $user = User::where('email', $email)
                    ->where('type', 'affiliate')
                    ->first();

            $order = Order::has('affiliate')->where('affiliate_id', $user->id)
                    ->where('commission_owed', $amount)
                    ->where('payout_status', 'unpaid')
                    ->first();

            return $order->update(['payout_status' => Order::STATUS_PAID]);
        } catch(\Exception $e) {
            dd($e->getMessage());
        }
    }
}
