<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile page (dashboard).
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        $section = $request->query('section', 'dashboard');

        // Stats for dashboard
        $stats = [
            'events_created' => Event::where('user_id', $user->id)->count(),
            'participations' => Booking::where('user_id', $user->id)->where('status', 'confirmee')->count(),
            'tickets_sold' => Booking::whereHas('event', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', 'confirmee')->sum('nb_places'),
        ];

        // Get user's created events
        $myEvents = Event::where('user_id', $user->id)
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        // Get all user's events for the events section
        $allMyEvents = Event::where('user_id', $user->id)
            ->with('category')
            ->withCount('bookings')
            ->latest()
            ->paginate(10);

        // Get user's bookings (participations)
        $myBookings = Booking::where('user_id', $user->id)
            ->with('event')
            ->latest()
            ->get();

        $allMyBookings = Booking::where('user_id', $user->id)
            ->with('event')
            ->latest()
            ->paginate(10);

        // VIP settings
        $vipPrice = (int) setting('vip_price', 5000);
        $vipDuration = (int) setting('vip_duration_days', 30);

        // Calculate VIP days remaining
        $vipDaysRemaining = 0;
        if ($user->isVip() && $user->vip_expires_at) {
            $vipDaysRemaining = now()->diffInDays($user->vip_expires_at, false);
        }

        return view('profile.show', compact(
            'user',
            'myEvents',
            'allMyEvents',
            'myBookings',
            'allMyBookings',
            'vipPrice',
            'vipDuration',
            'vipDaysRemaining',
            'section',
            'stats'
        ));
    }

    /**
     * Display the user's profile edit form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
