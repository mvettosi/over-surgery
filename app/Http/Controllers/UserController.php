<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $query = User::query();
        if ($request->input('date')) {
            $searchDate = Carbon::createFromFormat('Y-m-d', $request->input('date'));
        } else {
            $searchDate = Carbon::today();
        }

        if ($request->input('account_type')) {
            $query->where('account_type', '=', $request->input('account_type'));
        }
        if ($request->input('on_duty') || $request->input('available')) {
            $query->whereHas('schedules', function ($internalQuery) use ($searchDate) {
                $internalQuery->where($searchDate->format('D'), true)
                    ->whereDate('start_date', '<=', $searchDate)
                    ->whereDate('end_date', '>=', $searchDate);
            });
            if ($request->input('available')) {
                $query->withCount(['appointments' => function ($internalQuery) use ($searchDate) {
                    $internalQuery->whereDate('start_time', $searchDate->format('Y-m-d'));
                }]);
            }
        }

        if ($request->input('count')) {
            $result = $query->count();
        } else if ($request->input('first')) {
            $result = $query->first();
        } else {
            $result = $query->get();
        }

        if ($request->input('available')) {
            $filteredResult = [];
            foreach ($result as $user) {
                $schedules = Schedule::where('user_id', $user->id)
                    ->where($searchDate->format('D'), true)
                    ->whereDate('start_date', '<=', $searchDate)
                    ->whereDate('end_date', '>=', $searchDate)->get();
                $appointments = Appointment::where('doctor_or_nurse_id', $user->id)
                    ->whereDate('start_time', $searchDate->format('Y-m-d'))->get();
                $availableHours = [];
                foreach ($schedules as $schedule) {
                    $scheduleStart = Carbon::createFromFormat('H:i:s', $schedule->start_time)->hour;
                    $availableHours = array_merge($availableHours, range($scheduleStart, $scheduleStart + $schedule->duration - 1));
                }
                foreach ($appointments as $appointment) {
                    $bookedHour = Carbon::createFromFormat('Y-m-d H:i:s', $appointment->start_time)->hour;
                    if (($key = array_search($bookedHour, $availableHours)) !== false) {
                        unset($availableHours[$key]);
                    }
                }
                if (!empty($availableHours)) {
                    $temp = [];
                    foreach ($availableHours as $hour) {
                        array_push($temp, array('title' => $hour . ':00'));
                    }
                    $user->availableHours = $temp;
                    $filteredResult[] = $user;
                }
            }
            return $filteredResult;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user) {
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {
        //
    }
}
