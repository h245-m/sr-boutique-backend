<?php

use App\Models\ChatRoom;
use Illuminate\Support\Facades\Broadcast;

use function PHPUnit\Framework\isNull;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{chatRoom}', function ($user, ChatRoom $chatRoom) {

    if ($user->role("super_admin")){
        return true;
    }else {
        return $user->id == $chatRoom->user1_id || $user->id == $chatRoom->user2_id;
    }
    
});

Broadcast::channel('test', function ($user) {
    return !is_null($user);
});