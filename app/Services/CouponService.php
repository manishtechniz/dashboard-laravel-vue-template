<?php

namespace App\Services;

use App\Model\PromoCode;
use Carbon\Carbon;

class CouponService
{
    public function apply($code, $amount)
    {
        $coupon = PromoCode::where('code', $code)
            ->firstWhere('is_active', 1);

        $valid = $this->valid($coupon, $amount);

        if (! ($valid['status'] ?? false)) {
            return array_merge($valid, [
                'status' => false,
            ]);
        }

        return [
            'status' => true,
            'discount' => $this->calculateDiscount($coupon, $amount),
            'discount_type' => $coupon->type,
            'max_discount_amount' => $coupon->max_discount,
            'instance' => $coupon,
        ];
    }

    public function valid($coupon, $amount): array |bool
    {
        // Find coupon
        if (! $coupon) {
            return create422ErrorFormat('coupon_code', 'Invalid coupon code');
        }

        // Check date validity
        $now = Carbon::now();

        if ($coupon->start_date && $now->lt($coupon->start_date)) {
            return create422ErrorFormat('coupon_code', 'Coupon not started yet');
        }

        if ($coupon->end_date && $now->gt($coupon->end_date)) {
            return create422ErrorFormat('coupon_code', 'Coupon expired');
        }

        // Check usage limit
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return create422ErrorFormat('coupon_code', 'Coupon usage limit reached');
        }

        // Check minimum amount
        // if ($coupon->min_spend && $amount < $coupon->min_spend) {
        //     return create422ErrorFormat('coupon_code', 'Minimum order amount not reached. Atlease amount should be ' . $coupon->min_amount_for_apply, [
        //         'status' => false,
        //     ]);
        // }

        return [
            'status' => true,
        ];
    }

    public function calculateDiscount(PromoCode $coupon, float $amount): float
    {
        $discount = 0;

        if ($coupon->type === 'fixed') {
            $discount = $coupon->value;
        } elseif ($coupon->type === 'percentage') {
            $discount = ($amount * $coupon->value) / 100;

            // Apply max cap
            if ($coupon->max_discount) {
                $discount = min($discount, $coupon->max_discount);
            }
        }

        // Ensure discount not more than amount
        $discount = min($discount, $amount);

        return $discount;
    }
}
