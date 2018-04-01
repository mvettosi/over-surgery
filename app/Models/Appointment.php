<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model {
    /**
     * Get the doctor that owns the appointment.
     */
    public function doctor() {
        return $this->belongsTo('App\Models\User', 'doctor_or_nurse_id');
    }

    /**
     * Get the patient that owns the appointment.
     */
    public function patient() {
        return $this->belongsTo('App\Models\User', 'patient_id');
    }
}
