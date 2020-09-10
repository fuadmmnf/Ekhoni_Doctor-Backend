<?php


namespace App\Schedulers;


use App\Doctor;
use App\Doctorpayments;
use App\Patientcheckup;

class DoctorDailychecker
{
    public function __invoke()
    {
        $this->updateAllDoctorPendingAmounts();
    }

    private function updateAllDoctorPendingAmounts()
    {
        $doctors = Doctor::where('activation_status', 1)->get();
        foreach ($doctors as $doctor) {
            $doctorcheckups = Patientcheckup::where('doctor_id', $doctor->id)
                ->where('status', 1)
                ->get();
            $doctorCheckupAmount = 0;
            foreach ($doctorcheckups as $doctorcheckup) {
                $doctorCheckupAmount += $doctorcheckup->transaction->amount;
            }
            $doctorCheckupAmount = $doctorCheckupAmount - ($doctorCheckupAmount * $doctor->commission);
            $doctorPaymentsAmount = Doctorpayments::where('doctor_id', $doctor->id)->get()->sum('amount');
            $doctor->pending_amount = $doctorCheckupAmount - $doctorPaymentsAmount;
            $doctor->save();
        }
    }
}
