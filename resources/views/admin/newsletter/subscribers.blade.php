@extends('admin.layouts.app')

@section('title', 'Abonnés newsletter')

@section('content')
<div class="card" style="border-radius:12px; padding:24px;">
    <div class="page-header" style="margin-bottom:20px;">
        <div>
            <h2 class="page-title">Abonnés newsletter</h2>
            <p style="color:#6B7280; margin-top:6px;">Liste de tous les abonnés enregistrés.</p>
        </div>
        <a href="{{ route('admin.newsletter.index') }}" class="btn btn-outline">Retour</a>
    </div>

    <form method="GET" action="{{ route('admin.newsletter.subscribers') }}" style="margin-bottom:20px;">
        <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Rechercher par email" style="max-width:320px;">
    </form>

    <div style="display:grid; gap:12px;">
        @foreach($subscribers as $subscriber)
            <div style="border:1px solid #E5E7EB; border-radius:10px; padding:14px 16px; display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
                <div>
                    <div style="font-weight:600; color:#111827;">{{ $subscriber->email }}</div>
                    <div style="font-size:13px; color:#6B7280;">Inscrit le {{ $subscriber->created_at->translatedFormat('d M Y à H:i') }}</div>
                </div>
                <form action="{{ route('admin.newsletter.subscribers.destroy', $subscriber) }}" method="POST" onsubmit="return confirm('Supprimer cet abonné ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="padding:8px 12px;">Supprimer</button>
                </form>
            </div>
        @endforeach
    </div>

    <div style="margin-top:20px;">
        {{ $subscribers->links() }}
    </div>
</div>
@endsection
