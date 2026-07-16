<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\PaymentDataGrid;
use App\Model\Booking;
use App\Model\Payment;
use App\Model\Transaction;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(PaymentDataGrid::class)->process();
        }

        $bookings = Booking::all();
        return view('admin::payments.index', compact('bookings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'status' => 'required|string|in:pending,completed,failed,refunded',
            'transaction_reference' => 'nullable|string',
        ]);

        $payment = Payment::create($validated);

        // Auto create transaction record
        Transaction::create([
            'payment_id' => $payment->id,
            'booking_id' => $payment->booking_id,
            'amount' => $payment->amount,
            'type' => 'charge',
            'status' => $payment->status === 'completed' ? 'success' : 'pending',
            'reference' => $payment->transaction_reference,
        ]);

        return response()->json(['message' => 'Payment created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'status' => 'required|string|in:pending,completed,failed,refunded',
            'transaction_reference' => 'nullable|string',
        ]);

        $payment->update($validated);

        // Update transaction status
        Transaction::where('payment_id', $payment->id)->update([
            'amount' => $payment->amount,
            'status' => $payment->status === 'completed' ? 'success' : ($payment->status === 'failed' ? 'failed' : 'pending'),
        ]);

        return response()->json(['message' => 'Payment updated successfully.']);
    }
}
