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
     * ..
     * ],
     */
    public function getDoctorSchedulesByDoctorFromPresentDate(Doctor $doctor)
    {
        $doctorSchedulesByDoctorFromPresentDate = Doctorschedule::where('doctor_id', $doctor->id)
            ->whereDate('start_time', '>=', Carbon::now())
            ->whereDate('start_time', '<=', Carbon::now()->addDays(30))
            ->get();

        $doctorSchedulesByDoctorFromPresentDate = $doctorSchedulesByDoctorFromPresentDate->filter(function ($doctorSchedule) {
            $scheduleSlots = json_decode($doctorSchedule->schedule_slots, true);
            foreach ($scheduleSlots as $scheduleSlot){
                if($scheduleSlot['status'] == 0){
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
     * Doctorschedule store endpoint, returns doctorschedule instance. !! token required | admin:doctor, doctor
     *
     * @bodyParam doctor_id int required The doctor id.
     * @bodyParam  start_time string Duty start time for specialist. Example: "2020-07-29T14:30:00.000000Z"
     * @bodyParam  end_time string Duty end time for specialist. Example: "2020-07-29T18:30:00.000000Z"
     * @bodyParam  max_appointments_per_day int  Max number of appointments each day in case of specialist within start-end time.
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
            'max_appointments_per_day' => 'required| numeric',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);

        $newDoctorSchedule = new Doctorschedule();
        $newDoctorSchedule->doctor_id = $doctor->id;
        $newDoctorSchedule->start_time = Carbon::parse($request->start_time);
        $newDoctorSchedule->end_time = Carbon::parse($request->end_time);
        $newDoctorSchedule->max_appointments_per_day = $request->max_appointments_per_day;

        $scheduleInterval = floor(($newDoctorSchedule->end_time->diffInMinutes($newDoctorSchedule->start_time)) / $newDoctorSchedule->max_appointments_per_day);
        $time = $newDoctorSchedule->start_time;
        $appointmentSchedules = array();

        while ($time < $newDoctorSchedule->end_time) {
            $appointmentSchedules[] = [
                "time" => $time->copy(),
                "status" => 0 // 0 available, 1 booked
            ];
            $time = $time->addMinutes($scheduleInterval);
        }
        $newDoctorSchedule->schedule_slots = json_encode($appointmentSchedules);

        $newDoctorSchedule->save();
        return response()->json($newDoctorSchedule, 201);
    }


}
