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
        $pendingLetters = Letter::where('status','pending')->whereMonth('created_at',date('m'))->count();

        $letters = Letter::where('status','published')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
        ->whereIn('type', ['incoming', 'outgoing'])
        ->get();

        $dailyCounts = $letters->groupBy(function ($letter) {
            return $letter->created_at->format('Y-m-d');
        })->map(function ($group) {
            return [
                'incoming' => $group->where('type', 'incoming')->count(),
                'outgoing' => $group->where('type', 'outgoing')->count(),
            ];
        });

        $widget = [
            'users' => $users,
            'incoming' => $incoming,
            'outgoing' => $outgoing,
            'dispositions' => $dispositions,
            'pending' => $pendingLetters
        ];

        return view('home', [
            'widget' => $widget,
            'chart_data' => [
                'labels' => $dailyCounts->keys()->toArray(),
                'datasets' => [
                    [
                        'label' => 'Incoming',
                        'data' => $dailyCounts->pluck('incoming')->toArray(),
                        'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                        'borderColor' => 'rgba(75, 192, 192, 1)',
                        'borderWidth' => 1,
                        'fill' => false,
                    ],
                    [
                        'label' => 'Outgoing',
                        'data' => $dailyCounts->pluck('outgoing')->toArray(),
                        'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                        'borderColor' => 'rgba(255, 99, 132, 1)',
                        'borderWidth' => 1,
                        'fill' => false,
                    ],
                ],
            ]
        ]);
    }
}
