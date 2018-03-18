<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model {
    /**
     * The prescriptions that belong to the medication.
     */
    public function prescriptions() {
        return $this->belongsToMany('App\Models\Prescription');
    }
}
