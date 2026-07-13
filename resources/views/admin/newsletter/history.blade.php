@extends('admin.layouts.app')

@section('title', 'Historique newsletter')

@section('content')
<div class="card" style="border-radius:12px; padding:24px;">
    <div class="page-header" style="margin-bottom:20px;">
        <div>
            <h2 class="page-title">Historique des campagnes</h2>
            <p style="color:#6B7280; margin-top:6px;">Liste de toutes les campagnes envoyées.</p>
        </div>
        <a href="{{ route('admin.newsletter.index') }}" class="btn btn-outline">Retour</a>
    </div>

    <div style="display:grid; gap:12px;">
        @foreach($campaigns as $campaign)
            <div style="border:1px solid #E5E7EB; border-radius:10px; padding:14px 16px; display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
                <div>
                    <div style="font-weight:600; color:#111827;">{{ $campaign->sujet }}</div>
                    <div style="font-size:13px; color:#6B7280;">{{ $campaign->sender?->name ?? 'Admin' }} • {{ $campaign->sent_at?->translatedFormat('d M Y à H:i') }}</div>
                </div>
                <div style="font-size:13px; color:#CC0000; font-weight:600;">{{ $campaign->nb_destinataires }} destinataires</div>
            </div>
        @endforeach
    </div>

    <div style="margin-top:20px;">
        {{ $campaigns->links() }}
    </div>
</div>
@endsection
