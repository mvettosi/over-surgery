<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model {
    /**
     * The patient that owns this prescriptions.
     */
    public function patient() {
        return $this->belongsTo('App\Models\User', 'patient_id');
    }

    /**
     * The doctor that owns this prescriptions.
     */
    public function doctor() {
        return $this->belongsTo('App\Models\User', 'doctor_id');
    }

    /**
     * The endorsements included in this prescriptions.
     */
    public function endorsements() {
        return $this->hasMany('App\Models\Endorsement');
    }
}
