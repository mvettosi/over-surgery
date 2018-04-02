<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $query = Test::query();

        if ($request->input('patient_id')) {
            $query->where('patient_id', '=', $request->input('patient_id'));
        }
        if (!($request->input('include_checked') === 'true')) {
            $query->where('checked', false);
        }
        if ($request->input('with_doctor') === 'true') {
            $query->with('doctor');
        }
        $query->latest('date_taken');

        if ($request->input('count')) {
            return $query->count();
        } else if ($request->input('first') === 'true') {
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
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function show(Test $test) {
        $test->checked = true;
        $test->save();
        return response()->download(storage_path('tests/' . $test->result), $test->result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function edit(Test $test) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Test $test) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function destroy(Test $test) {
        //
    }
}
