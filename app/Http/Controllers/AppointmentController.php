<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $query->oldest('start_time');

        if ($request->input('count')) {
            return $query->count();
        } else if ($request->input('first')) {
            return $query->first();
        } else {
            return $query->get();
        }
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment) {
        //
    }
}
