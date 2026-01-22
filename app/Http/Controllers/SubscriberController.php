<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeSubscriber;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers,email'
        ]);

        Subscriber::create([
            'email' => $request->email,
            'status' => 'active'
        ]);

        try {
            Mail::to($request->email)->send(new WelcomeSubscriber($request->email));
        } catch (\Exception $e) {
            // Log email failure but don't stop the user experience
        }

        return back()->with('success', 'Thank you for subscribing! Please check your email.');
    }

    public function unsubscribe(Request $request)
    {
        if (!$request->has('email')) {
            return redirect('/')->with('error', 'Invalid unsubscribe link.');
        }

        $subscriber = Subscriber::where('email', $request->email)->first();

        if ($subscriber) {
            $subscriber->update(['status' => 'unsubscribed']);
            return redirect('/')->with('success', 'You have been successfully unsubscribed.');
        }

        return redirect('/')->with('error', 'Email not found.');
    }
}
