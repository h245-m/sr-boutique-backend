<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\ChatRoom;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageRequest $request)
    {
        $data = $request->validated();
        $user = $request->user;

        $chatRoom = ChatRoom::where([['user1_id', $data['user_id']] , ['user2_id' , $user->id]])->orWhere([['user1_id', $user->id] , ['user2_id' , $data['user_id']]])->first();
        
        if (!$chatRoom) {
            $chatRoom = ChatRoom::create([
                'user1_id' => $user->id,
                'user2_id' => $data['user_id'],
            ]);

            broadcast(new \App\Events\ChatRoomCreated($chatRoom));
        }

        $data['user_id'] = $user->id;
        $message = $chatRoom->messages()->create($data);

        broadcast(new \App\Events\MessageSent($message, $chatRoom));
        return response()->json(['message' => $message], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}
