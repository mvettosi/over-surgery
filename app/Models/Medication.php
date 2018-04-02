<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model {
    /**
     * The endorsements that refers to this medication.
     */
    public function endorsements() {
        return $this->hasMany('App\Models\Endorsement');
    }
}
