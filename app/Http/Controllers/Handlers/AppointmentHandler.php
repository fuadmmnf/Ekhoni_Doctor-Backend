<?php


namespace App\Http\Controllers\Handlers;


use App\Doctor;
use App\Doctorappointment;
use App\Doctorschedule;
use Carbon\Carbon;

class AppointmentHandler
{
//    public function cancelAppointmentBetweenTimeRange(Doctor $doctor, $start, $end)
//    {
//        $start_time = Carbon::parse($start);
//        $end_time = Carbon::parse($end);
//
//        $intersectingDoctorAppointments = Doctorappointment::where('status', 0)
//            ->where('doctor_id', $doctor->id)
//            ->whereNotBetween('start_time', [$start_time, $end_time])->get();
//        foreach ($intersectingDoctorAppointments as $intersectingDoctorAppointment) {
//            $intersectingDoctorAppointment->status = 1;
//            $intersectingDoctorAppointment->save();
//        }
//    }
//
//    public function checkIfDoctorHasRemainingAppointments(Doctor $doctor)
//    {
//
//        $activeDoctorAppointments = Doctorappointment::where('status', 0)
//            ->where('doctor_id', $doctor->id)
//            ->where('status', 0)
//            ->get();
//
//        return $activeDoctorAppointments->count() > 0;
//    }

    public function setAppointmentInDoctorSchedule(Doctorappointment $doctorappointment){
        $doctorSchedule = Doctorschedule::where('doctor_id', $doctorappointment->doctor_id)
            ->whereDate('start_time', '=', $doctorappointment->start_time)
            ->first();

        $scheduleSlots = json_decode($doctorSchedule->schedule_slots);
        for ($i=0; $i<count($scheduleSlots); $i++){
            if(abs(Carbon::parse($scheduleSlots[$i]->time)->diffInMinutes($doctorappointment->start_time)) < 1){
                $scheduleSlots[$i]->status = 1;
                break;
            }
        }

        $doctorSchedule->schedule_slots = json_encode($scheduleSlots);
        $doctorSchedule->save();
    }
}
