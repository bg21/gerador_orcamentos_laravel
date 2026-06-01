<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $totalClients = $user->clients()->count();
        $totalServices = $user->services()->count();

        $quotesQuery = $user->quotes();
        $totalQuotes = (clone $quotesQuery)->count();

        $approvedAmount = (clone $quotesQuery)->where('status', 'approved')->sum('total_amount');
        $pendingAmount = (clone $quotesQuery)->where('status', 'sent')->sum('total_amount');

        $recentQuotes = (clone $quotesQuery)
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalClients',
            'totalServices',
            'totalQuotes',
            'approvedAmount',
            'pendingAmount',
            'recentQuotes'
        ));
    }
}
