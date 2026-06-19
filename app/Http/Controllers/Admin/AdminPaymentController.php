<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with('user', 'event')
            ->when($request->statut, function ($query, $statut) {
                return $query->where('statut', $statut);
            })
            ->when($request->type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => Payment::where('statut', 'success')->sum('montant'),
            'this_month' => Payment::where('statut', 'success')
                ->whereMonth('created_at', now()->month)
                ->sum('montant'),
            'tickets' => Payment::where('statut', 'success')
                ->where('type', 'ticket')
                ->sum('montant'),
            'premium' => Payment::where('statut', 'success')
                ->where('type', 'premium')
                ->sum('montant'),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function show(Payment $payment)
    {
        $payment->load('user', 'event', 'ticket');
        return view('admin.payments.show', compact('payment'));
    }
}