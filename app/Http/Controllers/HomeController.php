<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Letter;
use App\Models\Dispositions;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        $users = User::count();
        $incoming = Letter::incoming()->where('status', 'published')->whereMonth('created_at', date('m'))->count();
        $outgoing = Letter::outgoing()->where('status', 'published')->whereMonth('created_at', date('m'))->count();
        $dispositions = Dispositions::where('user_id', $user->id)->where('status', 'pending')->count();
        $pendingIncoming = Letter::incoming()->where('status','pending')->whereMonth('created_at',date('m'))->count();
        $pendingOutgoing = Letter::outgoing()->where('status', 'pending')->whereMonth('created_at', date('m'))->count();
        $widget = [
            'users' => $users,
            'incoming' => $incoming,
            'outgoing' => $outgoing,
            'dispositions' => $dispositions,
            'pending' => [
                'incoming' => $pendingIncoming,
                'outgoing' => $pendingOutgoing,
            ]
        ];

        return view('home', compact('widget'));
    }
}
