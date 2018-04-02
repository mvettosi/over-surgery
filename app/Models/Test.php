<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model {
    /**
     * The doctor that owns this prescriptions.
     */
    public function doctor() {
        return $this->belongsTo('App\Models\User', 'doctor_id');
    }
}
