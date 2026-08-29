<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Admin\DataGrids\PaymentDataGrid;
use App\Model\Booking;
use App\Model\Payment;
use App\Model\Transaction;
use App\Model\Client;
use App\Model\ClientBalance;
use App\Services\ClientBalanceService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AdminPaymentController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(PaymentDataGrid::class)->process();
        }

        $bookings = Booking::all();
        $clients = Client::all();

        $paymentStatusTypes = PaymentStatus::details();
        $paymentMethodTypes = PaymentMethod::details();

        return view('admin::payments.index', compact('bookings', 'clients', 'paymentStatusTypes', 'paymentMethodTypes'));
    }

    public function store(Request $request, ClientBalanceService $balanceService)
    {
        $validated = $request->validate([
            'payment_type' => 'required|string|in:advance,due_clearance,withdrawal_advance',
            'client_id' => 'nullable|exists:clients,id',
            'booking_id'   => [
                'nullable',
                'required_if:payment_type,due_clearance',
                Rule::exists('bookings', 'id')->where('client_id', $request->client_id),
            ],
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:' . implode(',', PaymentMethod::values()),
            // 'status' => 'required|string|in:' . implode(',', PaymentStatus::values()),
            'transaction_reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $validated['status'] = PaymentStatus::PAID->value;

            $amount = $validated['amount'];
            $paymentMethod = $validated['payment_method'];

            if ($validated['payment_type'] === 'due_clearance') {
                $booking = Booking::where('id', $validated['booking_id'])->lockForUpdate()->first();

                if ($booking->due_amount < $amount) {
                    return response()->json(create422ErrorFormat('general', 'Sorry!, Amount should be less or equal to due booking amount'), 422);
                }

                $booking->paid_amount += $amount;

                $booking->due_amount -= $amount;

                $booking->save();
            } else {
                $validated['booking_id'] = null;
            }

            $recordedBy = Auth::guard('admin')->id();

            $validated['recorded_by'] = $recordedBy;

            $payment = Payment::create($validated);

            Transaction::create([
                'payment_id' => $payment->id,
                'booking_id' => $payment->booking_id,
                'amount' => $payment->amount,
                'type' => 'payment',
                'payment_method' => $payment->payment_method,
                'status' => $payment->status,
                'reference' => $payment->transaction_reference,
                'notes' => $payment->notes,
                'recorded_by' => $recordedBy,
            ]);

            if ($payment->status === PaymentStatus::PAID->value && $payment->client_id) {
                $desc = ucfirst(str_replace('_', ' ', $payment->payment_type)) . ' Payment';

                if ($payment->notes) {
                    $desc .= ' - ' . $payment->notes;
                }

                $bSReq = [
                    'clientId' => $payment->client_id,
                    'bookingId' => $payment->booking_id,
                    'advanceAmount' => 0,
                    'advanceType' => 'credit',
                    'dueAmount' => 0,
                    'dueType' => 'credit',
                    'paymentMethod' => $paymentMethod,
                    'description' => $desc,
                ];

                if ($payment->payment_type === 'due_clearance') {
                    $bSReq = array_merge($bSReq, [
                        'dueAmount' => $amount,
                        'dueType' => 'debit',
                    ]);
                } else if ($payment->payment_type === 'advance') {
                    if ($paymentMethod == PaymentMethod::WALLET->value) {
                        $paymentMethod = PaymentMethod::CASH->value;
                    }

                    $bSReq = array_merge($bSReq, [
                        'advanceAmount' => $amount,
                        'advanceType' => 'credit',
                    ]);
                } else if ($payment->payment_type === 'withdrawal_advance') {
                    $paymentMethod = PaymentMethod::WALLET->value;

                    $bSReq = array_merge($bSReq, [
                        'advanceAmount' => -$amount,
                        'advanceType' => 'debit',
                    ]);
                }

                $balanceResponse = $balanceService->manage($bSReq);

                if (!$balanceResponse['status']) {
                    DB::rollBack();
                    return response()->json(create422ErrorFormat('general', $balanceResponse['message'] ?? 'Sorry!, Encounter error during payment. Please try again..'), 422);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Payment created successfully.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Sorry!, Encounter error during payment. Please try again']);
        }
    }

    // public function update(Request $request, ClientBalanceService $balanceService, $id)
    // {
    //     $validated = $request->validate([
    //         'amount' => 'required|numeric|min:0',
    //         'status' => 'required|string|in:' . implode(',', PaymentStatus::values()),
    //         'transaction_reference' => 'nullable|string|max:2000',
    //         'notes' => 'nullable|string|max:2000',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         $payment = Payment::lockForUpdate()->find($id);

    //         $oldAmount = $payment->amount;
    //         $oldStatus = $payment->status;

    //         $newAmount = $validated['amount'];
    //         $newStatus = $validated['status'];

    //         // 1. Calculate Effective Amounts based on Status
    //         // Only 'paid' statuses actually apply to balances. Refunds or pending are treated as 0 contribution.
    //         $effectiveOldAmount = ($oldStatus === PaymentStatus::PAID->value) ? (float) $oldAmount : 0.0;
    //         $effectiveNewAmount = ($newStatus === PaymentStatus::PAID->value) ? (float) $newAmount : 0.0;

    //         // 2. Calculate the Delta (Difference)
    //         // A negative delta means we are revoking/refunding. A positive means we are adding new money.
    //         $deltaAmount = $effectiveNewAmount - $effectiveOldAmount;

    //         // 3. Update the Payment Record
    //         $payment->update($validated);

    //         $recordedBy = Auth::guard('admin')->id();

    //         // 4. Record the Transaction History
    //         Transaction::create([
    //             'payment_id' => $payment->id,
    //             'booking_id' => $payment->booking_id,
    //             'amount' => $payment->amount,
    //             'type' => 'payment_update',
    //             'payment_method' => $payment->payment_method,
    //             'status' => $payment->status,
    //             'reference' => $payment->transaction_reference,
    //             'notes' => $payment->notes,
    //             'recorded_by' => $recordedBy,
    //         ]);

    //         // 5. Apply Financial Adjustments ONLY if the effective amount changed
    //         if ($deltaAmount !== 0.0) {

    //             // Adjust Booking if applicable
    //             if ($payment->payment_type === 'due_clearance' && !empty($payment->booking_id)) {
    //                 $booking = Booking::where('id', $payment->booking_id)->lockForUpdate()->first();

    //                 if ($booking) {
    //                     // Prevent overpaying beyond what is currently due (only applies if we are ADDING money)
    //                     if ($deltaAmount > 0 && $booking->due_amount < $deltaAmount) {
    //                         return response()->json(create422ErrorFormat('general', 'Sorry! Amount cannot exceed the remaining due booking amount.'), 422);
    //                     }

    //                     $booking->paid_amount += $deltaAmount;
    //                     $booking->due_amount -= $deltaAmount;

    //                     // Failsafe
    //                     $booking->due_amount = max(0, $booking->due_amount);
    //                     $booking->save();
    //                 }
    //             }

    //             // Prepare Description and Action Type for Ledger
    //             $desc = ucfirst(str_replace('_', ' ', $payment->payment_type)) . ' Payment Adjusted';
    //             $actionFor = 'payment_update';

    //             if ($newStatus === 'refunded' || $newStatus === 'refund') {
    //                 $desc = ucfirst(str_replace('_', ' ', $payment->payment_type)) . ' Payment Refunded';
    //                 $actionFor = 'refund';
    //             } elseif ($oldStatus !== PaymentStatus::PAID->value && $newStatus === PaymentStatus::PAID->value) {
    //                 $desc = ucfirst(str_replace('_', ' ', $payment->payment_type)) . ' Payment Received';
    //                 $actionFor = 'payment_received';
    //             }

    //             if ($payment->notes) {
    //                 $desc .= ' - ' . $payment->notes;
    //             }

    //             // Map Delta to the Correct Service Variables
    //             $dueAmountPass = 0;
    //             $advanceAmountPass = 0;

    //             if ($payment->payment_type === 'due_clearance') {
    //                 $dueAmountPass = $deltaAmount;
    //             } elseif ($payment->payment_type === 'advance') {
    //                 $advanceAmountPass = $deltaAmount;
    //             }

    //             // Determine Ledger Types
    //             $dueType = $dueAmountPass >= 0 ? 'debit' : 'credit';
    //             $advanceType = $advanceAmountPass >= 0 ? 'credit' : 'debit';

    //             // Call Client Balance Service
    //             // Because your service uses total_due -= dueAmount and total_advance += advanceAmount, 
    //             // passing a negative delta (refund) will mathematically REVERSE the previous action perfectly!
    //             $balanceResponse = $balanceService->manage([
    //                 'clientId' => $payment->client_id,
    //                 'bookingId' => $payment->booking_id,
    //                 'paymentId' => $payment->id,
    //                 'advanceAmount' => $advanceAmountPass,
    //                 'dueAmount' => $dueAmountPass,
    //                 'advanceType' => $advanceType,
    //                 'dueType' => $dueType,
    //                 'paymentMethod' => $payment->payment_method,
    //                 'description' => $desc,
    //                 'callFrom' => '',
    //                 'actionFor' => $actionFor,
    //             ]);

    //             if (!$balanceResponse['status']) {
    //                 DB::rollBack();
    //                 return response()->json(create422ErrorFormat('general', $balanceResponse['message'] ?? 'Sorry! Encountered an error during balance adjustment. Please try again.'), 422);
    //             }
    //         }

    //         DB::commit();
    //         return response()->json(['message' => 'Payment updated successfully.']);
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         return response()->json(create422ErrorFormat('general', 'Sorry! Encountered an error during payment update. Please try again. ' . $th->getMessage()), 422);
    //     }
    // }

    public function updateBackup(Request $request, ClientBalanceService $balanceService, $id)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:' . implode(',', PaymentStatus::values()),
            'transaction_reference' => 'nullable|string|max:2000',
            'notes' => 'nullable|string|max:2000',
        ]);

        DB::beginTransaction();

        try {
            $payment = Payment::lockForUpdate()->find($id);

            $oldAmount = $payment->amount;
            $oldStatus = $payment->status;
            $newAmount = $validated['amount'] ?? 0;

            $payment->update($validated);

            $recordedBy = Auth::guard('admin')->id();

            // Transaction::create([
            //     'payment_id' => $payment->id,
            //     'booking_id' => $payment->booking_id,
            //     'amount' => $payment->amount,
            //     'type' => 'payment',
            //     'payment_method' => $payment->payment_method,
            //     'status' => $payment->status,
            //     'reference' => $payment->transaction_reference,
            //     'notes' => $payment->notes,
            //     'recorded_by' => $recordedBy,
            // ]);

            // if ($payment->payment_type === 'due_clearance') {
            //     $booking = Booking::where('id', $validated['booking_id'])->lockForUpdate()->first();

            //     if ($booking && $booking->due_amount < $amount) {
            //         return response()->json(create422ErrorFormat('general', 'Sorry!, Amount should be less or equal to due booking amount'), 422);
            //     }

            //     $booking->paid_amount += $amount;

            //     $booking->due_amount -= $amount;

            //     $booking->save();
            // } else {
            //     $validated['booking_id'] = null;
            // }

            // if (($payment->status === PaymentStatus::PAID->value)) {
            //     $desc = ucfirst(str_replace('_', ' ', $payment->payment_type)) . ' Payment';

            //     if ($payment->notes) {
            //         $desc .= ' - ' . $payment->notes;
            //     }

            //     if ($payment->payment_type === 'due_clearance') {
            //         $balanceResponse = $balanceService->manage([
            //             'clientId' => $payment->client_id,
            //             'bookingId' => $payment->booking_id,
            //             'advanceAmount' => 0,
            //             'advanceType' => 'credit',
            //             'dueAmount' => $amount,
            //             'dueType' => 'debit',
            //             'paymentMethod' => $paymentMethod,
            //             'description' => $desc,
            //         ]);
            //     } else if ($payment->payment_type === 'advance') {
            //         $balanceResponse = $balanceService->manage([
            //             'clientId' => $payment->client_id,
            //             'bookingId' => $payment->booking_id,
            //             'advanceAmount' => $amount,
            //             'advanceType' => 'credit',
            //             'dueAmount' => 0,
            //             'dueType' => 'credit',
            //             'paymentMethod' => $paymentMethod,
            //             'description' => $desc,
            //         ]);
            //     }

            //     if (!$balanceResponse['status']) {
            //         DB::rollBack();
            //         return response()->json(create422ErrorFormat('general', $balanceResponse['message'] ?? 'Sorry!, Encounter error during payment. Please try again..'), 422);
            //     }
            // }

            DB::commit();
            return response()->json(['message' => 'Payment updated successfully.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Sorry!, Encounter error during payment update. Please try again']);
        }
    }
    public function massDestroy(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
        ]);

        try {
            Payment::whereIn('id', $validated['indices'])->delete();
            return response()->json(['message' => 'Payments deleted successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during mass delete.'], 500);
        }
    }

    public function massUpdate(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
            'value' => 'required|string|in:pending,completed,failed,refunded',
        ]);

        try {
            Payment::whereIn('id', $validated['indices'])->update(['status' => $validated['value']]);

            $transactionStatus = $validated['value'] === 'completed' ? 'success' : ($validated['value'] === 'failed' ? 'failed' : 'pending');
            Transaction::whereIn('payment_id', $validated['indices'])->update(['status' => $transactionStatus]);

            return response()->json(['message' => 'Payments status updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during mass update.'], 500);
        }
    }

    public function transactions(Request $request)
    {
        $query = Transaction::query()->with('payment');

        if ($request->has('payment_id') && $request->payment_id !== 'all' && $request->payment_id !== null) {
            $query->where('payment_id', $request->payment_id);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($transactions);
    }
}
