<?php

namespace App\Services;

use App\Enums\PaymentMethod;
use App\Model\ClientBalance;
use App\Model\ClientLedger;
use Illuminate\Support\Facades\DB;

class ClientBalanceService
{
    /**
     * Record a charge/due (debit) and update the client's balance.
     */
    public function manage(array $data)
    {
        return DB::transaction(function () use ($data) {
            $defaults = [
                'paymentId' => null,
                'bookingId' => null,
                'paymentMethod' => null,
                'description' => null,
                'callFrom' => 'default',
                'actionFor' => 'default',
                'dueAmount' => 0,
                'advanceAmount' => 0,
            ];

            $data = array_merge($defaults, $data);

            [
                'paymentMethod' => $paymentMethod,
                'paymentId' => $paymentId,
                'bookingId' => $bookingId,
                'clientId' => $clientId,
                'description' => $description,
                'dueAmount' => $dueAmount,
                'advanceAmount' => $advanceAmount,
                'dueType' => $dueType,
                'advanceType' => $advanceType,
                'callFrom' => $callFrom,
                'actionFor' => $actionFor,
            ] = $data;

            if ($advanceAmount === 0 && $dueAmount === 0) {
                return [
                    'status' => true,
                ];
            }

            if (
                $paymentMethod == PaymentMethod::WALLET->value
                && !$this->isSufficientBalance($clientId, $advanceAmount)
            ) {
                return [
                    'status' => false,
                    'message' => 'Sorry!, Client wallet has insufficient balance.'
                ];
            }

            $balance = ClientBalance::firstOrCreate(
                ['client_id' => $clientId],
                ['total_due' => 0.00, 'total_advance' => 0.00]
            );

            $balance = ClientBalance::where('id', $balance->id)->lockForUpdate()->first();

            if ($callFrom == 'booking' || $callFrom == 'payment_adjustment') {
                // Here, adjuestment from booking updatation
                $balance->total_due += $dueAmount; // can be +/- value
                $balance->total_advance += $advanceAmount; // can be +/- value 
            } else {
                // Here, from payment collection
                if ($paymentMethod == PaymentMethod::WALLET->value) {
                    $balance->total_advance -= $dueAmount;
                    $advanceType = 'debit';
                }

                $balance->total_due -= $dueAmount; //it always negative
                $balance->total_advance += $advanceAmount; // can be +/- value
            }

            // Failsafe: Prevent negative balances if old data was out of sync
            $balance->total_due = max(0, $balance->total_due);
            $balance->total_advance = max(0, $balance->total_advance);

            $balance->save();

            ClientLedger::create([
                'client_id' => $clientId,
                'booking_id' => $bookingId,
                'payment_id' => $paymentId,
                'due_type' => $dueType,
                'advance_type' => $advanceType,
                'advance_amount' => abs($advanceAmount),
                'due_amount' => abs($dueAmount),
                'advance_after' => $balance->total_advance,
                'due_after' => $balance->total_due,
                'description' => $description,
                'action_for' => $actionFor,
            ]);

            return [
                'status' => true,
            ];
        });
    }

    public function isSufficientBalance($clientId, $amount)
    {
        $clientBalance = ClientBalance::where('client_id', $clientId)->first();

        return $clientBalance && $clientBalance->total_advance >= $amount;
    }
}
