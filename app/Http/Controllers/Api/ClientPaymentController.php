<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Booking;
use App\Model\Payment;
use App\Model\Transaction;
use Illuminate\Http\Request;

class ClientPaymentController extends Controller
{
    public function pay(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'token' => 'required|string', // stripe token simulation
        ]);

        $booking = Booking::findOrFail($validated['booking_id']);

        // Create Payment record
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'status' => 'completed',
            'transaction_reference' => 'ch_' . uniqid()
        ]);

        // Create Transaction log
        $transaction = Transaction::create([
            'payment_id' => $payment->id,
            'booking_id' => $booking->id,
            'amount' => $payment->amount,
            'type' => 'charge',
            'status' => 'success',
            'reference' => $payment->transaction_reference,
            'response_payload' => [
                'gateway' => 'stripe_simulated',
                'status' => 'paid',
                'fee_collected' => 0.50
            ]
        ]);

        // Update booking status
        $booking->update(['status' => 'confirmed']);

        return response()->json([
            'message' => 'Payment processed and booking confirmed.',
            'payment' => $payment,
            'transaction' => $transaction
        ]);
    }
}
