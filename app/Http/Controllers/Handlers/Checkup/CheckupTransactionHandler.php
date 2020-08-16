<?php


namespace App\Http\Controllers\Handlers\Checkup;


use App\Doctor;
use App\Doctorappointment;
use App\Patient;
use App\Patientcheckup;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CheckupTransactionHandler
{
    private $userCreditLimit = 100;

    private function createUserDebitTransaction(User $user, $amount): Transaction
    {
        $checkupTransaction = new Transaction();
        $checkupTransaction->user_id = $user->id;
        $checkupTransaction->type = 0; // 0 => debit, 1 => credit
        $checkupTransaction->status = 1; // 0 => initialized, 1 => complete
        $checkupTransaction->amount = $amount;
        do {
            $code = Str::random(16);
            $transaction = Transaction::where('code', $code)->first();
        } while ($transaction);
        $checkupTransaction->code = $code;

        $checkupTransaction->save();
        $user->balance = $user->balance - $amount;
        $user->save();

        return $checkupTransaction;
    }


    public function checkUserAccountBalanceAndProceedTransaction(Patient $patient, Doctor $doctor, $isAppointment): ?Transaction
    {
        $user = $patient->user;
        $rate = $doctor->offer_rate;
        if ($isAppointment) {
            $checkupIds = Patientcheckup::where('patient_id', $patient->id)
                ->where('doctor_id', $doctor->id)
                ->pluck('id');
            $patientPreviousAppointment = Doctorappointment::whereIn('patientcheckup_id', $checkupIds)
                ->orderBy('start_time', 'desc')->first();
            if (!$patientPreviousAppointment && $doctor->first_appointment_rate != null) {
                $rate = $doctor->first_appointment_rate;
            } elseif ($patientPreviousAppointment && Carbon::now()->diffInDays(Carbon::parse($patientPreviousAppointment->start_time)) < 10){
                $rate = $doctor->report_followup_rate;
            }

        }

        $remainingAmountAfterTransaction = $user->balance + $this->userCreditLimit - $rate;

        if ($remainingAmountAfterTransaction >= 0) {
            return $this->createUserDebitTransaction($user, $rate);
        } else {
            return null;
        }
    }


    public function createNewCheckup(Patient $patient, Doctor $doctor, ?Carbon $start_time, ?Carbon $end_time): ?Patientcheckup
    {
        $newPatientcheckup = new Patientcheckup();
        $newPatientcheckup->patient_id = $patient->id;
        $newPatientcheckup->doctor_id = $doctor->id;
        $newPatientcheckup->start_time = $start_time;
        $newPatientcheckup->end_time = $end_time;

        $transaction = $this->checkUserAccountBalanceAndProceedTransaction($patient, $doctor, $start_time == null);

        if ($transaction) {
            $newPatientcheckup->transaction_id = $transaction->id;
        } else {
            return null;
        }

        do {
            $code = Str::random(16);
            $patientCheckup = Patientcheckup::where('code', $code)->first();
        } while ($patientCheckup);
        $newPatientcheckup->code = $code;

        $newPatientcheckup->save();
        return $newPatientcheckup;
    }
}
