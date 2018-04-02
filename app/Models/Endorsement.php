<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Endorsement extends Model {
    /**
     * The prescription that owns this Endorsement.
     */
    public function prescription() {
        return $this->belongsTo('App\Models\Prescription');
    }

    /**
     * The medication that owns this Endorsement.
     */
    public function medication() {
        return $this->belongsTo('App\Models\Medication');
    }
}
