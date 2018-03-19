<?php

namespace App\Http\Controllers;

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

        if ($request->input('account_type')) {
            $query->where('account_type', '=', $request->input('account_type'));
        }
        if ($request->input('on_duty') || $request->input('available')) {
            $query->whereHas('schedules', function ($internalQuery) {
                $now = Carbon::now();
                $internalQuery->where($now->format('D'), true)
                    ->whereTime('start_time', '<=', $now->format('H:i'))
                    ->whereTime('end_time', '>=', $now->format('H:i'));
            });
            if ($request->input('available')) {
                $query->whereDoesntHave('appointments', function ($internalQuery) {
                    $now = Carbon::now();
                    $internalQuery->where('start_time', '<=', $now)
                    ->where('end_time', '>=', $now);
                });
            }
        }

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
