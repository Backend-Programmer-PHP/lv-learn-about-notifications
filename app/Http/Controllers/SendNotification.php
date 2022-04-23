<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\LearnNotification;
use Pusher\Pusher;
class SendNotification extends Controller
{
    public function create()
    {
        return view('notification');
    }
    public function store(Request $request)
    {
        $user = User::find(1); // id của user mình đã đăng kí ở trên, user này sẻ nhận được thông báo
        $data = $request->only([
            'title',
            'content',
        ]);
        $user->notify(new LearnNotification($data));
        $options = array(
            'cluster' => 'ap1',
            'encrypted' => true
        );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $pusher->trigger('NotificationEvent', 'send-message', $data);
        return view('notification');
    }
}
