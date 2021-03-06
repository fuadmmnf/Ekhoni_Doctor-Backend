<?php

namespace App\Http\Controllers\Api;

use App\Doctor;
use App\Doctorappointment;
use App\Doctorschedule;
use App\Doctortype;
use App\Http\Controllers\Handlers\DoctorScheduleHandler;
use App\Http\Controllers\Handlers\TokenUserHandler;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * @group  Doctor Schedule management
 *
 * APIs related to Doctor
 */
class DoctorScheduleController extends Controller
{
    protected $user;

    public function __construct(Request $request)
    {
        $this->user = $request->user('sanctum');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    public function getAvaialbleFreeDoctorSchedules(){
        if (!$this->user) {
            return response()->json('Forbidden Access', 403);
        }


        $doctorSchedulesByDoctorFromPresentDate = Doctorschedule::whereDate('start_time', '>=', Carbon::now())
            ->where('end_time', '>', Carbon::now())
            ->where('end_time', '<=', Carbon::now()->addDays(7))
            ->where('type', 2)
            ->with('doctor')
            ->with('doctor.user')
            ->paginate(30);

        $doctorSchedulesByDoctorFromPresentDate->data = $doctorSchedulesByDoctorFromPresentDate->filter(function ($doctorSchedule) {
            $scheduleSlots = json_decode($doctorSchedule->schedule_slots, true);
            foreach ($scheduleSlots as $scheduleSlot) {
                if ($scheduleSlot['status'] == 0) {
                    return true;
                }
            }
            return false;
        })->values();

        return response()->json($doctorSchedulesByDoctorFromPresentDate);
    }

    /**
     * Fetch Doctor Schedules By Doctor
     *
     * Fetch doctor schedules starting from present date to upcoming 30days, Schedule Will not be shown if all slots are booked, response of doctorschedule instances.
     *
     * @urlParam  doctor required The Doctor ID of doctor schedules.
     *
     * @response  200 [
     * {
     * "doctor_id": 1,
     * "start_time": "2020-07-29T18:30:00.000000Z",
     * "end_time": "2020-07-29T18:30:00.000000Z",
     * "max_appointments_per_day": 4,
     * "schedule_slots": "[{\"time\":\"2020-07-29T14:30:00.000000Z\",\"status\":0},{\"time\":\"2020-07-29T15:30:00.000000Z\",\"status\":0},{\"time\":\"2020-07-29T16:30:00.000000Z\",\"status\":0},{\"time\":\"2020-07-29T17:30:00.000000Z\",\"status\":0}]",
     * "updated_at": "2020-07-25T21:11:49.000000Z",
     * "created_at": "2020-07-25T21:11:49.000000Z",
     * "id": 6
     * }
     * ]
     */
    public function getDoctorSchedulesByDoctorFromPresentDate(Doctor $doctor, $type)
    {
        $doctorSchedulesByDoctorFromPresentDate = Doctorschedule::where('doctor_id', $doctor->id)
            ->whereDate('start_time', '>=', Carbon::now())
            ->whereDate('end_time', '<=', Carbon::now()->addDays(30))
            ->where('type', $type)
            ->get();

        $doctorSchedulesByDoctorFromPresentDate = $doctorSchedulesByDoctorFromPresentDate->filter(function ($doctorSchedule) {
            $scheduleSlots = json_decode($doctorSchedule->schedule_slots, true);
            foreach ($scheduleSlots as $scheduleSlot) {
                if ($scheduleSlot['status'] == 0) {
                    return true;
                }
            }
            return false;
        })->values();
        return response()->json($doctorSchedulesByDoctorFromPresentDate);
    }


    /**
     * _Create Doctorschedule_
     *
     * Doctorschedule store endpoint(appointment duration set to 20minutes), returns doctorschedule instance. !! token required | admin:doctor, doctor
     *
     * @bodyParam doctor_id int required The doctor id.
     * @bodyParam  start_time string Duty start time for specialist. Example: "2020-07-29T14:30:00.000000Z"
     * @bodyParam  end_time string Duty end time for specialist. Example: "2020-07-29T18:30:00.000000Z"
     *
     *
     * @response  201 {
     * "doctor_id": 1,
     * "start_time": "2020-07-29T14:30:00.000000Z",
     * "end_time": "2020-07-29T18:30:00.000000Z",
     * "max_appointments_per_day": 4,
     * "schedule_slots": "[{\"time\":\"2020-07-29T14:30:00.000000Z\",\"status\":0},{\"time\":\"2020-07-29T15:30:00.000000Z\",\"status\":0},{\"time\":\"2020-07-29T16:30:00.000000Z\",\"status\":0},{\"time\":\"2020-07-29T17:30:00.000000Z\",\"status\":0}]",
     * "updated_at": "2020-07-25T21:11:49.000000Z",
     * "created_at": "2020-07-25T21:11:49.000000Z",
     * "id": 6
     * }
     *
     * @response 204 ""
     * @response 400 {"message": "Conflicting schedules, failed to create new schedule"}
     */
    public function store(Request $request)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:doctor') &&
            !$this->user->hasRole('doctor')) {
            return response()->json('Forbidden Access', 403);
        }

        $this->validate($request, [
            'doctor_id' => 'required| numeric',
            'start_time' => 'required',
            'end_time' => 'required',
            'type' => 'required| numeric'
//            'max_appointments_per_day' => 'required| numeric',
        ]);


        $doctor = Doctor::findOrFail($request->doctor_id);
        $startTime = Carbon::parse($request->start_time);
        $endTime = Carbon::parse($request->end_time);

        $doctorSchedules = Doctorschedule::where('doctor_id', $doctor->id)
            ->whereDate('start_time', $startTime)
            ->get();

        foreach ($doctorSchedules as $doctorSchedule) {
            if (Carbon::parse($doctorSchedule->start_time)->lte($startTime) && Carbon::parse($doctorSchedule->end_time)->gte($startTime)) {
                return response()->noContent();

            }
            if (Carbon::parse($doctorSchedule->start_time)->lte($endTime) && Carbon::parse($doctorSchedule->end_time)->gte($endTime)) {
                return response()->json(["message" => "Conflicting schedules, failed to create new schedule"], 400);

            }
        }

        $newDoctorSchedule = new Doctorschedule();
        $newDoctorSchedule->doctor_id = $doctor->id;
        $newDoctorSchedule->type = $request->type;
        $newDoctorSchedule->start_time = $startTime;
        $newDoctorSchedule->end_time = $endTime;

//  ->default(0)      $scheduleInterval = floor(($newDoctorSchedule->end_time->diffInMinutes($newDoctorSchedule->start_time)) / $newDoctorSchedule->max_appointments_per_day);
        $scheduleInterval = 10;
        if($newDoctorSchedule->type == 1){
            $scheduleInterval = 15;
        }
        $time = $newDoctorSchedule->start_time->copy();
        $isTimeLeftForAppointment = $newDoctorSchedule->start_time->copy()->addMinutes($scheduleInterval);
        $appointmentSchedules = array();

        $numAppointments = 0;
        while ($time < $newDoctorSchedule->end_time && $isTimeLeftForAppointment <= $newDoctorSchedule->end_time) {
            $appointmentSchedules[] = [
                "time" => $time->copy()->toDateTimeString(),
                "status" => 0 // 0 available, 1 booked
            ];
            $time->addMinutes($scheduleInterval);
            $isTimeLeftForAppointment->addMinutes($scheduleInterval);

            $numAppointments++;
        }
        $newDoctorSchedule->schedule_slots = json_encode($appointmentSchedules);
        $newDoctorSchedule->max_appointments_per_day = $numAppointments;
        $newDoctorSchedule->slots_left = $numAppointments;
        $newDoctorSchedule->save();
        return response()->json($newDoctorSchedule, 201);
    }

    public function delete(Doctorschedule $doctorschedule)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:doctor') &&
            !$this->user->hasRole('doctor')) {
            return response()->json('Forbidden Access', 403);
        }

        if ($doctorschedule->max_appintments_per_day != $doctorschedule->slots_left) {
            return response()->json(["status" => "schedule booking has been made"], 400);
        }
        $doctorschedule->delete();
        return response()->noContent();
    }
}
