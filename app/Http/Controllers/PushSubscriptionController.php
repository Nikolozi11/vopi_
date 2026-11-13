<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'endpoint' => 'required',
            'keys.p256dh' => 'required',
            'keys.auth' => 'required',
        ]);

        $user = auth()->user();

        $user->pushSubscriptions()->updateOrCreate(
            ['endpoint' => $request->endpoint],
            [
                'keys_p256dh' => $request->keys['p256dh'],
                'keys_auth' => $request->keys['auth'],
            ]
        );

        return response()->json(['success' => true]);
    }
}