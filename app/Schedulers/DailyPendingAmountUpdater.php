<?php


namespace App\Schedulers;


use App\Agentpayments;
use App\Doctor;
use App\Doctorpayments;
use App\Patientcheckup;
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
        }
    }
}
