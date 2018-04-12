<?php

namespace App\Http\Controllers;

use App\Events\ChatRequest;
use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class MessageController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $query = Message::query();
        if ($request->input('requests') === 'true') {
            $query->where('recipient_id', null);
        }
        $query->with('sender');
        $query->with('recipient');

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
        $currentUser = Auth::user();
        $validations = [
            'message' => 'required',
        ];
        // if ($currentUser->account_type == 'receptionist') {
        //     $validations[] = ['recipient_id' => 'required'];
        // }
        $validator = Validator::make($request->all(), $validations);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $message = new Message;
        $message->message = $request->message;
        $message->sender_id = $currentUser->id;
        $message->recipient_id = $request->recipient_id;
        $message->sender_type = $currentUser->account_type;
        $message->save();

        $msgTOSend = Message::with('sender')->with('recipient')->find($message->id);
        if ($currentUser->account_type == 'patient' && (!$request->recipient_id || $request->recipient_id == '')) {
            //Message from patient without recipient: it's a chat request
            event(new ChatRequest($msgTOSend));
        } else {
            event(new MessageSent($msgTOSend));
        }

        return response()->json($message, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message) {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message) {
        $message->delete();
        return response()->json([
            'message' => 'The message was successfully deleted.',
        ], 200);
    }
}
