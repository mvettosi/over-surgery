<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model {
    /**
     * The medications that belong to the prescriptions.
     */
    public function medications() {
        return $this->belongsToMany('App\Models\Medication');
    }
}
