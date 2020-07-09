<?php


namespace App\Http\Controllers\Handlers;


use App\Doctor;
use App\Patient;
use App\Patientcheckup;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CheckupTransactionHandler
{
    private $userCreditLimit = 100;

    private function confirmUserDebitTransaction(User $user, $amount, $status): Transaction{
        $checkupTransaction = new Transaction();
        $checkupTransaction->user_id = $user->id;
        $checkupTransaction->type = 0; // 0 => debit, 1 => credit
        $checkupTransaction->status = $status; // 0 => initialized, 1 => complete
        $checkupTransaction->amount = $amount;
        do
        {
            $code = Str::random(16);
            $transaction = Transaction::where('code', $code)->first();
        }
        while($transaction);
        $checkupTransaction->code = $code;

        if($user->is_agent){
            $checkupTransaction->agent_commission = $user->agent_percentage;
        }
        $checkupTransaction->save();
        $user->amount = $user->amount - $amount;
        $user->save();

        return $checkupTransaction;
    }


    public function checkUserAccountBalanceAndProceedTransaction(User $user, Doctor $doctor, $status): ?Transaction
    {
        $remainingAmountAfterTransaction = $user->amount + $this->userCreditLimit - $doctor->offer_rate;

        if($remainingAmountAfterTransaction >= 0){
            return $this->confirmUserDebitTransaction($user, $doctor->offer_rate, $status);
        } else{
            return null;
        }
    }



    public function createNewCheckup(Patient $patient, Doctor $doctor, Carbon $start_time,  Carbon $end_time): ?Patientcheckup
    {
        $user = $patient->user;

        $newPatientcheckup = new Patientcheckup();
        $newPatientcheckup->patient_id = $patient->id;
        $newPatientcheckup->doctor_id = $doctor->id;
        $newPatientcheckup->start_time = $start_time;
        $newPatientcheckup->end_time = $end_time;

        $transaction = $this->checkUserAccountBalanceAndProceedTransaction($user, $doctor, 1);

        if ($transaction) {
            $newPatientcheckup->transaction_id = $transaction->id;
        } else {
            return null;
        }

        do {
            $code = Str::random(16);
            $patientCheckup = Patientcheckup::where('code', $code)->first();
        } while ($patientCheckup);


        $newPatientcheckup->save();
        return $newPatientcheckup;
    }
}
