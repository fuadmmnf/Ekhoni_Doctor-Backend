<?php


namespace App\Http\Controllers\Handlers;


use App\Doctor;
use App\Doctorappointment;
use Carbon\Carbon;

class AppointmentHandler
{
    public function cancelAppointmentBetweenTimeRange(Doctor $doctor, $start, $end)
    {
        $start_time = Carbon::parse($start);
        $end_time = Carbon::parse($end);

        $intersectingDoctorAppointments = Doctorappointment::where('status', 0)
            ->where('doctor_id', $doctor->id)
            ->whereNotBetween('start_time', [$start_time, $end_time])->get();
        foreach ($intersectingDoctorAppointments as $intersectingDoctorAppointment) {
            $intersectingDoctorAppointment->status = 1;
            $intersectingDoctorAppointment->save();
        }
    }
}
