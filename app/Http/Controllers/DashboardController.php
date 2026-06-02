<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        /** @var \App\Models\User $user */
        $user   = Auth::user();
        $userId = $user->id;

        // ── KPIs ──────────────────────────────────────────────────────────────
        $totalClients  = $user->clients()->count();
        $totalServices = $user->services()->count();
        $quotesQuery   = $user->quotes();
        $totalQuotes   = (clone $quotesQuery)->count();
        $approvedAmount = (clone $quotesQuery)->where('status', 'approved')->sum('total_amount');
        $pendingAmount  = (clone $quotesQuery)->where('status', 'sent')->sum('total_amount');

        $recentQuotes = (clone $quotesQuery)
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ── GRÁFICO 1: Receita aprovada dos últimos 12 meses ──────────────────
        $months      = collect();
        $revenueData = collect();

        for ($i = 11; $i >= 0; $i--) {
            $date  = Carbon::now()->subMonths($i);
            $total = Quote::where('user_id', $userId)
                ->where('status', 'approved')
                ->whereYear('issue_date', $date->year)
                ->whereMonth('issue_date', $date->month)
                ->sum('total_amount');

            $months->push($date->locale('pt_BR')->translatedFormat('M/y'));
            $revenueData->push(round((float) $total, 2));
        }

        // ── GRÁFICO 2: Distribuição de status ─────────────────────────────────
        $statusCounts = Quote::where('user_id', $userId)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusLabels = ['draft' => 'Rascunho', 'sent' => 'Enviado', 'approved' => 'Aprovado', 'declined' => 'Recusado'];
        $donutLabels  = [];
        $donutData    = [];
        $donutColors  = [];
        $colorMap     = [
            'draft'    => '#94a3b8',
            'sent'     => '#3b82f6',
            'approved' => '#22c55e',
            'declined' => '#ef4444',
        ];

        foreach ($statusLabels as $key => $label) {
            $count = $statusCounts->get($key, 0);
            if ($count > 0 || $totalQuotes === 0) {
                $donutLabels[] = $label;
                $donutData[]   = (int) $count;
                $donutColors[] = $colorMap[$key];
            }
        }

        // Taxa de conversão (aprovados / total enviados+aprovados+recusados)
        $actionable      = ($statusCounts->get('approved', 0) + $statusCounts->get('declined', 0) + $statusCounts->get('sent', 0));
        $conversionRate  = $actionable > 0
            ? round(($statusCounts->get('approved', 0) / $actionable) * 100, 1)
            : 0;

        return view('dashboard', compact(
            'totalClients',
            'totalServices',
            'totalQuotes',
            'approvedAmount',
            'pendingAmount',
            'recentQuotes',
            'months',
            'revenueData',
            'donutLabels',
            'donutData',
            'donutColors',
            'conversionRate'
        ));
    }
}
