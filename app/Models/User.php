<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject {
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }

    /**
     * Get the schedules for the user.
     */
    public function schedules() {
        return $this->hasMany('App\Models\Schedule');
    }

    /**
     * Get the appointments for witch the user is a doctor.
     */
    public function appointmentsAsDoctor() {
        return $this->hasMany('App\Models\Appointment', 'doctor_or_nurse_id');
    }

    /**
     * Get the appointments for witch the user is a patient.
     */
    public function appointmentsAsPatient() {
        return $this->hasMany('App\Models\Appointment', 'patient_id');
    }
}
