@extends('admin.layouts.app')

@section('title', $user->name)

@section('content')
<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 class="page-title">Profil de {{ $user->name }}</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline">← Retour</a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    {{-- User Info --}}
    <div class="card">
        <div style="text-align: center; margin-bottom: 24px;">
            @if($user->avatar)
                <img src="{{ Storage::url($user->avatar) }}" alt="" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin-bottom: 16px;">
            @else
                <div style="width: 100px; height: 100px; border-radius: 50%; background: #E5E7EB; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 32px; color: #6B7280; margin: 0 auto 16px;">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
            @endif
            <h2 style="font-size: 20px; font-weight: 600;">{{ $user->name }}</h2>
            <p style="color: #6B7280;">{{ $user->email }}</p>
            @if($user->role === 'admin')
                <span style="background: #8B1A1A; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px;">Administrateur</span>
            @endif
        </div>

        <div style="border-top: 1px solid #E5E7EB; padding-top: 16px;">
            <p style="font-size: 14px; color: #6B7280; margin-bottom: 8px;">Membre depuis</p>
            <p style="font-weight: 500;">{{ $user->created_at->format('d/m/Y') }}</p>
        </div>

        @if($user->phone)
        <div style="margin-top: 16px;">
            <p style="font-size: 14px; color: #6B7280; margin-bottom: 8px;">Téléphone</p>
            <p style="font-weight: 500;">{{ $user->phone }}</p>
        </div>
        @endif
    </div>

    {{-- User Activity --}}
    <div style="display: flex; flex-direction: column; gap: 24px;">
        {{-- Events --}}
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px;">Événements ({{ $user->events->count() }})</h3>
            @forelse($user->events as $event)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #E5E7EB;">
                <div>
                    <p style="font-weight: 500;">{{ $event->titre }}</p>
                    <p style="font-size: 13px; color: #6B7280;">{{ $event->date->format('d/m/Y') }}</p>
                </div>
                <span class="badge badge-{{ $event->statut === 'publie' ? 'success' : ($event->statut === 'en_attente' ? 'warning' : 'danger') }}">
                    {{ $event->statut }}
                </span>
            </div>
            @empty
            <p style="color: #6B7280; text-align: center; padding: 20px;">Aucun événement</p>
            @endforelse
        </div>

        {{-- Payments --}}
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px;">Paiements ({{ $user->payments->count() }})</h3>
            @forelse($user->payments as $payment)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #E5E7EB;">
                <div>
                    <p style="font-weight: 500;">{{ $payment->event->titre ?? 'N/A' }}</p>
                    <p style="font-size: 13px; color: #6B7280;">{{ $payment->created_at->format('d/m/Y') }}</p>
                </div>
                <div style="text-align: right;">
                    <p style="font-weight: 600;">{{ number_format($payment->montant, 0, ',', ' ') }} FCF</p>
                    <span class="badge badge-{{ $payment->statut === 'success' ? 'success' : 'warning' }}">
                        {{ $payment->statut }}
                    </span>
                </div>
            </div>
            @empty
            <p style="color: #6B7280; text-align: center; padding: 20px;">Aucun paiement</p>
            @endforelse
        </div>
    </div>
</div>
@endsection