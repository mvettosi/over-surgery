<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if ($request->input('on_duty') === 'true' || $request->input('available') === 'true') {
            $query->whereHas('schedules', function ($internalQuery) use ($searchDate) {
                $internalQuery->where($searchDate->format('D'), true)
                    ->whereDate('start_date', '<=', $searchDate)
                    ->whereDate('end_date', '>=', $searchDate);
            });
            if ($request->input('available') === 'true') {
                $query->withCount(['appointmentsAsDoctor' => function ($internalQuery) use ($searchDate) {
                    $internalQuery->whereDate('start_time', $searchDate->format('Y-m-d'));
                }]);
            }
        }

        if ($request->input('count') === 'true') {
            return $query->count();
        } else if ($request->input('first') === 'true') {
            $result = $query->first();
        } else {
            $result = $query->get();
        }

        if ($request->input('available') === 'true') {
            $filteredResult = [];
            if ($request->input('first') === 'true') {
                $tempResult = [];
                $tempResult[] = $result;
            } else {
                $tempResult = $result;
            }

            foreach ($tempResult as $user) {
                $hours = UserController::getAvailableHours($user, $searchDate);
                if (!empty($hours)) {
                    $user->availableHours = $hours;
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
        if ($request->input('old_password') && $request->input('new_password')) {
            if (password_verify($request->input('old_password'), $user->password)) {
                $user->password = bcrypt($request->input('new_password'));
            } else {
                return response()->json([
                    'message' => 'Wrong password.',
                ], 403);
            }
            $user->pwd_updated = true;
            $user->save();

            return response()->json([
                'message' => 'The password was successfully changed.',
            ], 200);
        }
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

    public static function getAvailableHours(User $user, Carbon $searchDate) {
        $schedules = Schedule::where('user_id', $user->id)
            ->where($searchDate->format('D'), true)
            ->whereDate('start_date', '<=', $searchDate)
            ->whereDate('end_date', '>=', $searchDate)->get();
        $appointments = Appointment::whereDate('start_time', $searchDate->format('Y-m-d'))
            ->where(function ($query) use ($user) {
                $query->where('doctor_or_nurse_id', $user->id)
                    ->orWhere('patient_id', Auth::user()->id);
            })->get();
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
            $result = [];
            foreach ($availableHours as $hour) {
                array_push($result, array('title' => $hour . ':00'));
            }
            return $result;
        }
    }
}
