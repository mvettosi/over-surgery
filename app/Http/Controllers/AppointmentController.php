<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UserController;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $query = Appointment::query();
        if ($request->input('date')) {
            $searchDate = Carbon::createFromFormat('Y-m-d', $request->input('date'));
        } else {
            $searchDate = Carbon::today();
        }

        if ($request->input('patient_id')) {
            $query->where('patient_id', '=', $request->input('patient_id'));
        }
        if ($request->input('doctor_or_nurse_id')) {
            $query->where('doctor_or_nurse_id', '=', $request->input('doctor_or_nurse_id'));
        }
        if ($request->input('date')) {
            $query->whereDate('start_time', $searchDate->format('Y-m-d'));
        } else if (!$request->input('includePast')) {
            $query->where('start_time', '>=', $searchDate);
        }
        if ($request->input('with_patient') === 'true') {
            $query->with('patient');
        }
        if ($request->input('with_doctor') === 'true' || $request->input('with_availability') === 'true') {
            $query->with('doctor');
        }
        $query->oldest('start_time');

        if ($request->input('count') === 'true') {
            return $query->count();
        } else if ($request->input('first') === 'true') {
            $result = $query->first();
        } else {
            $result = $query->get();
        }

        if ($request->input('with_availability') === 'true') {
            if ($request->input('first') === 'true') {
                $tempResult = [];
                $tempResult[] = $result;
            } else {
                $tempResult = $result;
            }
            foreach ($tempResult as $appointment) {
                $hours = UserController::getAvailableHours($appointment->doctor, $searchDate);
                if (!empty($hours)) {
                    $appointment->doctor->availableHours = $hours;
                }
            }
            $result = $tempResult;
        }

        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $currentUser = Auth::user();
        $validator = Validator::make($request->all(), [
            'start_time' => 'required',
            'doctor_or_nurse_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        //Check that the appointment's start time was not taken since last refresh
        if (Appointment::where('doctor_or_nurse_id', $request->doctor_or_nurse_id)->where('start_time', $request->start_time)->count() > 0) {
            return response()->json([
                'message' => 'The time you selected was just taken!' . PHP_EOL . 'Refresh the search results and try again. ',
            ], 409);
        }
        $docOrNurse = User::find($request->doctor_or_nurse_id);
        $appointment = new Appointment;
        $appointment->start_time = $request->start_time;
        $appointment->location = $docOrNurse->address;
        $appointment->patient_id = $currentUser->id;
        $appointment->doctor_or_nurse_id = $request->doctor_or_nurse_id;
        $appointment->save();
        return response()->json([
            'message' => 'The appointment was successfully booked.',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment) {
        return $appointment;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment) {
        $validator = Validator::make($request->all(), [
            'start_hour' => 'integer|between:0,24'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
        if (Appointment::where('doctor_or_nurse_id', $request->doctor_or_nurse_id)->where('start_time', $request->start_time)->count() > 0) {
            return response()->json([
                'message' => 'The time you selected was just taken!' . PHP_EOL . 'Refresh the search results and try again. ',
            ], 409);
        }

        if ($request->input('start_hour')) {
            $start_time = Carbon::createFromFormat('Y-m-d H:i:s', $appointment->start_time);
            $start_time->hour = $request->input('start_hour');
            $appointment->start_time = $start_time;
        }
        $appointment->save();
        return response()->json([
            'message' => 'The appointment was successfully booked.',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment) {
        $appointment->delete();
        return response()->json([
            'message' => 'The appointment was successfully deleted.',
        ], 200);
    }
}
