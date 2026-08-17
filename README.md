$oldDueAmount = $booking->due_amount;
            $oldAdvanceAmount = 0;

            if ($booking->paid_amount > $booking->total_amount_incl_tax) {
                $oldAdvanceAmount = abs($booking->paid_amount - $booking->total_amount_incl_tax);
            }

            $dueAmount = $advanceAmount = 0;

            if ($paidAmount > $totalAmountInclTax) {
                $advanceAmount = abs($totalAmountInclTax - $paidAmount);
            } else if ($paidAmount < $totalAmountInclTax) {
                $dueAmount = abs($paidAmount - $totalAmountInclTax);
            }

            $finalDueAmount = $dueAmount - $oldDueAmount;

            // dump($dueAmount, $oldDueAmount, $finalDueAmount);
            $finalAdvanceAmount = $advanceAmount - $oldAdvanceAmount;

            $dueType = $advanceType = 'credit';

            if ($finalDueAmount < 0) {
                $dueType = 'debit';
            }

            if ($finalAdvanceAmount < 0) {
                $advanceType = 'debit';
            }