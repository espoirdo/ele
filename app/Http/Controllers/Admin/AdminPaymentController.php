<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\PremiumPayment;
use App\Models\VipPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        // Agrège les 3 sources de paiements (tickets, premium, VIP) dans une collection unifiée
        $tickets = Booking::with(['user', 'event'])
            ->where('status', 'confirmee')
            ->get()
            ->map(function ($b) {
                return (object) [
                    'id'             => 'B-' . $b->id,
                    'source'         => 'booking',
                    'source_id'      => $b->id,
                    'user'           => $b->user,
                    'event'          => $b->event,
                    'montant'        => (int) $b->total,
                    'statut'         => 'success',
                    'methode'        => $b->moyen_paiement,
                    'type'           => 'ticket',
                    'transaction_id' => $b->paygate_identifier ?? $b->paygate_tx_reference,
                    'created_at'     => $b->created_at,
                ];
            });

        $premiums = PremiumPayment::with(['user', 'event'])
            ->where('statut', 'confirme')
            ->get()
            ->map(function ($p) {
                return (object) [
                    'id'             => 'P-' . $p->id,
                    'source'         => 'premium',
                    'source_id'      => $p->id,
                    'user'           => $p->user,
                    'event'          => $p->event,
                    'montant'        => (int) $p->total,
                    'statut'         => $p->statut,
                    'methode'        => $p->moyen_paiement,
                    'type'           => 'premium',
                    'transaction_id' => $p->transaction_id,
                    'created_at'     => $p->created_at,
                ];
            });

        $vips = VipPayment::with(['user'])
            ->where('statut', 'confirme')
            ->get()
            ->map(function ($v) {
                return (object) [
                    'id'             => 'V-' . $v->id,
                    'source'         => 'vip',
                    'source_id'      => $v->id,
                    'user'           => $v->user,
                    'event'          => null,
                    'montant'        => (int) $v->montant,
                    'statut'         => $v->statut,
                    'methode'        => $v->methode,
                    'type'           => 'vip',
                    'transaction_id' => $v->transaction_id,
                    'created_at'     => $v->created_at,
                ];
            });

        $all = $tickets->concat($premiums)->concat($vips)->sortByDesc('created_at');

        // Filtres
        if ($request->type) {
            $all = $all->where('type', $request->type)->values();
        }
        if ($request->statut) {
            $all = $all->where('statut', $request->statut)->values();
        }

        $payments = new \Illuminate\Pagination\LengthAwarePaginator(
            $all->forPage(\Illuminate\Pagination\Paginator::resolveCurrentPage(), 20),
            $all->count(),
            20,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(),
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $stats = [
            'total'      => (int) Booking::where('status', 'confirmee')->sum('total')
                + (int) PremiumPayment::where('statut', 'confirme')->sum('total')
                + (int) VipPayment::where('statut', 'confirme')->sum('montant'),
            'this_month' => (int) Booking::where('status', 'confirmee')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total')
                + (int) PremiumPayment::where('statut', 'confirme')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total')
                + (int) VipPayment::where('statut', 'confirme')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('montant'),
            'tickets'    => (int) Booking::where('status', 'confirmee')->sum('total'),
            'premium'    => (int) PremiumPayment::where('statut', 'confirme')->sum('total'),
            'vip'        => (int) VipPayment::where('statut', 'confirme')->sum('montant'),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function show(Request $request, string $id)
    {
        // id au format "B-12" / "P-3" / "V-5"
        [$source, $sourceId] = explode('-', $id, 2);

        if ($source === 'B') {
            $payment = Booking::with(['user', 'event'])->findOrFail((int) $sourceId);
        } elseif ($source === 'P') {
            $payment = PremiumPayment::with(['user', 'event'])->findOrFail((int) $sourceId);
        } elseif ($source === 'V') {
            $payment = VipPayment::with(['user'])->findOrFail((int) $sourceId);
        } else {
            // Anciens paiements de la table `payments` (héritage éventuel)
            $payment = Payment::with(['user', 'event', 'ticket'])->findOrFail((int) $id);
        }

        return view('admin.payments.show', compact('payment'));
    }
}