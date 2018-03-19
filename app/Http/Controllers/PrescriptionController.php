<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PrescriptionController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $query = Prescription::query();
        $now = Carbon::now();

        if ($request->input('patient_id')) {
            $query->where('patient_id', '=', $request->input('patient_id'));
        }
        if (!$request->input('include_expired')) {
            $query->whereDate('expiration_date', '>=', $now->toDateString());
        }
        if ($request->input('expiring')) {
            $inThreeDays = $now->addDays(3);
            $query->whereDate('expiration_date', '<=', $inThreeDays->toDateString());
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
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function show(Prescription $prescription) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function edit(Prescription $prescription) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prescription $prescription) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prescription $prescription) {
        //
    }
}
