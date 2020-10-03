<?php


namespace App\Schedulers;


use App\Agentpayments;
use App\Dailycommission;
use App\Doctor;
use App\Doctorpayments;
use App\Patientcheckup;
use App\User;
use Carbon\Carbon;

class DailyPendingAmountUpdater
{
    public function __invoke()
    {
        $this->updateAllDoctorPendingAmounts();
        $this->updateAllAgentPendingAmounts();
    }

    private function updateAllDoctorPendingAmounts()
    {
        $doctors = Doctor::where('activation_status', 1)->get();
        foreach ($doctors as $doctor) {
            $doctorcheckups = Patientcheckup::where('doctor_id', $doctor->id)
                ->whereDate('start_time', Carbon::now()->subDay())
                ->where('status', 1)
                ->get();
            $doctorCheckupAmount = 0;
            foreach ($doctorcheckups as $doctorcheckup) {
                $doctorCheckupAmount += $doctorcheckup->transaction->amount;
            }
            $doctorCheckupAmount = $doctorCheckupAmount - ($doctorCheckupAmount * $doctor->commission);
//            $doctorPaymentsAmount = Doctorpayments::where('doctor_id', $doctor->id)->get()->sum('amount');
            $doctor->pending_amount = $doctor->pending_amount + $doctorCheckupAmount;
            $doctor->save();

            $user = $doctor->user;
            $user->pending_amount = $doctor->pending_amount;
            $user->save();

            $newDailyCommission = new Dailycommission();
            $newDailyCommission->user_id = $user->id;
            $newDailyCommission->type = 0;
            $newDailyCommission->commission = $doctor->commission;
            $newDailyCommission->amount = $doctorCheckupAmount;
            $newDailyCommission->user_total = $doctor->pending_amount;
            $newDailyCommission->save();

        }
    }

    private function updateAllAgentPendingAmounts()
    {
        $agents = Doctor::where('is_agent', true)->get();
        foreach ($agents as $agent) {
            $agentPatients = $agent->patients()->pluck('id');
            $agentCheckups = Patientcheckup::where('patient_id', $agentPatients)
                ->whereDate('start_time', Carbon::now()->subDay())
                ->where('status', 1)
                ->get();
            $agentCheckupAmount = 0;
            foreach ($agentCheckups as $agentcheckup) {
                $agentCheckupAmount += $agentcheckup->transaction->amount;
            }
            $agentCheckupAmount = $agentCheckupAmount * $agent->agent_percentage;
//            $agentPaymentsAmount = Agentpayments::where('agent_id', $agent->id)->get()->sum('amount');
            $agent->pending_amount = $agent->pending_amount + $agentCheckupAmount;
            $agent->save();

            $newDailyCommission = new Dailycommission();
            $newDailyCommission->user_id = $agent->id;
            $newDailyCommission->type = 1;
            $newDailyCommission->commission = $agent->agent_percentage;
            $newDailyCommission->amount = $agentCheckupAmount;
            $newDailyCommission->user_total = $agent->pending_amount;
            $newDailyCommission->save();
        }
    }


}
