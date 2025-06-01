<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chats.{id}', function ($user, $id) {
    return \App\Models\Chat::find($id)?->users->contains($user->id);
});
