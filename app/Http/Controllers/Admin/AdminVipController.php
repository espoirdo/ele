<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VipPayment;
use Illuminate\Http\Request;

class AdminVipController extends Controller
{
    /**
     * Display VIP members list
     */
    public function members(Request $request)
    {
        $query = User::where('is_vip', true);

        // Filter by status
        if ($request->status === 'expired') {
            $query->where('is_vip', true)
                ->whereNotNull('vip_expires_at')
                ->where('vip_expires_at', '<', now());
        } elseif ($request->status === 'active') {
            $query->where('is_vip', true)
                ->whereNotNull('vip_expires_at')
                ->where('vip_expires_at', '>', now());
        } elseif ($request->status === 'simple') {
            $query->where('is_vip', false);
        }

        $vipMembers = $query->latest()->paginate(20);

        // Get all members count for stats
        $stats = [
            'total' => User::count(),
            'vip_active' => User::where('is_vip', true)
                ->whereNotNull('vip_expires_at')
                ->where('vip_expires_at', '>', now())
                ->count(),
            'vip_expired' => User::where('is_vip', true)
                ->whereNotNull('vip_expires_at')
                ->where('vip_expires_at', '<', now())
                ->count(),
            'simple' => User::where('is_vip', false)->count(),
        ];

        return view('admin.vip.members', compact('vipMembers', 'stats'));
    }

    /**
     * Display VIP payments list
     */
    public function payments(Request $request)
    {
        $payments = VipPayment::with('user')
            ->when($request->statut, function ($query, $statut) {
                return $query->where('statut', $statut);
            })
            ->latest()
            ->paginate(20);

        return view('admin.vip.payments', compact('payments'));
    }

    /**
     * Manually activate VIP for a user
     */
    public function activate(Request $request, User $user)
    {
        $vipDuration = (int) setting('vip_duration_days', 30);

        $newExpiresAt = $user->isVip() && $user->vip_expires_at && $user->vip_expires_at->isFuture()
            ? $user->vip_expires_at->addDays($vipDuration)
            : now()->addDays($vipDuration);

        $user->update([
            'is_vip' => true,
            'vip_subscribed_at' => $user->isVip() ? $user->vip_subscribed_at : now(),
            'vip_expires_at' => $newExpiresAt,
        ]);

        return back()->with('success', 'VIP activé pour ' . $user->name);
    }

    /**
     * Revoke VIP from a user
     */
    public function revoke(User $user)
    {
        $user->update([
            'is_vip' => false,
            'vip_expires_at' => null,
        ]);

        return back()->with('success', 'VIP révoqué pour ' . $user->name);
    }

    /**
     * Manually confirm a pending VIP payment
     */
    public function confirmPayment(VipPayment $vipPayment)
    {
        if ($vipPayment->statut !== 'en_attente') {
            return back()->with('error', 'Ce paiement n\'est pas en attente.');
        }

        $vipPayment->update(['statut' => 'confirme']);

        // Activate VIP for the user
        $vipDuration = (int) setting('vip_duration_days', 30);
        $user = $vipPayment->user;

        $newExpiresAt = $user->isVip() && $user->vip_expires_at && $user->vip_expires_at->isFuture()
            ? $user->vip_expires_at->addDays($vipDuration)
            : now()->addDays($vipDuration);

        $user->update([
            'is_vip' => true,
            'vip_subscribed_at' => $user->isVip() ? $user->vip_subscribed_at : now(),
            'vip_expires_at' => $newExpiresAt,
        ]);

        return back()->with('success', 'Paiement confirmé et VIP activé pour ' . $user->name);
    }
}