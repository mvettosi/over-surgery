<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {
    /**
     * Get the sender of this message.
     */
    public function sender() {
        return $this->belongsTo('App\Models\User', 'sender_id');
    }

    /**
     * Get the recipient this message.
     */
    public function recipient() {
        return $this->belongsTo('App\Models\User', 'recipient_id');
    }
}
