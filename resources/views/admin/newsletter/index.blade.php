@extends('admin.layouts.app')

@section('title', 'Newsletter')

@section('content')
<div style="display:grid; gap:24px; grid-template-columns: 1.2fr 0.8fr; align-items:start;">
    <div class="card" style="border-radius:12px; padding:28px;">
        <div style="background:#CC0000; color:white; padding:18px 24px; border-radius:12px 12px 0 0; margin:-28px -28px 24px; font-family:'Eras Medium ITC', Arial, sans-serif; font-size:22px; font-weight:700;">
            Envoyer une newsletter
        </div>

        <form action="{{ route('admin.newsletter.send') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="sujet">Sujet</label>
                <input id="sujet" name="sujet" type="text" class="form-input" placeholder="Ex: Nouveaux événements à Lomé ce week-end" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="contenu">Contenu du message</label>
                <textarea id="contenu" name="contenu" class="form-input" rows="10" style="min-height:200px; font-family:'Calibri', Arial, sans-serif;" placeholder="Rédigez votre message ici..." required></textarea>
            </div>

            <div class="form-group">
                <button type="button" class="btn btn-outline" style="border-color:#CC0000; color:#CC0000; border-radius:40px;" onclick="document.getElementById('previewModal').style.display='block'">Aperçu avant envoi</button>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; border-radius:40px;" onclick="return confirm('Vous allez envoyer cet email à {{ $totalCount }} abonnés. Continuer ?')">
                Envoyer à tous les abonnés
            </button>
        </form>
    </div>

    <div style="display:grid; gap:24px;">
        <div class="card" style="border-radius:12px; padding:24px;">
            <h3 style="font-size:18px; color:#1A1A1A; margin-bottom:16px;">Statistiques des abonnés</h3>
            <div style="display:grid; gap:12px;">
                <div style="background:#FFF5F5; border-radius:10px; padding:16px;">
                    <div style="font-size:30px; font-weight:700; color:#CC0000;">{{ $totalCount }}</div>
                    <div style="color:#6B7280; font-size:13px;">Abonnés au total</div>
                </div>
                <div style="background:#FFF5F5; border-radius:10px; padding:16px;">
                    <div style="font-size:30px; font-weight:700; color:#CC0000;">{{ $activeCount }}</div>
                    <div style="color:#6B7280; font-size:13px;">Abonnés actifs</div>
                </div>
                <div style="background:#FFF5F5; border-radius:10px; padding:16px;">
                    <div style="font-size:16px; font-weight:600; color:#1A1A1A;">{{ $lastSubscriber ? $lastSubscriber->created_at->translatedFormat('d M Y') : 'Aucun' }}</div>
                    <div style="color:#6B7280; font-size:13px;">Dernier abonnement</div>
                </div>
            </div>

            <div style="margin-top:20px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                    <h4 style="font-size:15px;">Derniers abonnés</h4>
                    <a href="{{ route('admin.newsletter.subscribers') }}" class="btn btn-outline" style="padding:8px 12px; font-size:12px;">Voir tous les abonnés</a>
                </div>
                <div style="display:grid; gap:8px;">
                    @foreach($subscribers as $subscriber)
                        <div style="padding:10px 12px; border:1px solid #E5E7EB; border-radius:8px; display:flex; justify-content:space-between; gap:8px; align-items:center;">
                            <div>
                                <div style="font-size:13px; font-weight:600; color:#111827;">{{ $subscriber->email }}</div>
                                <div style="font-size:12px; color:#6B7280;">{{ $subscriber->created_at->translatedFormat('d M Y') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-top:24px; border-radius:12px; padding:24px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h3 style="font-size:18px; color:#1A1A1A;">Historique des campagnes</h3>
        <a href="{{ route('admin.newsletter.history') }}" class="btn btn-outline" style="padding:8px 12px; font-size:12px;">Voir tout l'historique</a>
    </div>
    @if($campaigns->isEmpty())
        <p style="color:#6B7280;">Aucune campagne envoyée pour le moment.</p>
    @else
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
    @endif
</div>
<div id="previewModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:9999; padding:24px;">
    <div style="max-width:640px; margin:40px auto; background:white; border-radius:16px; padding:24px; position:relative;">
        <button type="button" onclick="document.getElementById('previewModal').style.display='none'" style="position:absolute; top:12px; right:12px; border:none; background:#f3f4f6; width:32px; height:32px; border-radius:50%; cursor:pointer;">×</button>
        <h3 style="font-size:18px; margin-bottom:12px; color:#CC0000;">Aperçu de l'email</h3>
        <div style="border:1px solid #E5E7EB; border-radius:10px; padding:16px; white-space:pre-wrap; font-family:'Calibri', Arial, sans-serif; color:#333;">
            <strong>Sujet :</strong> <span id="previewSubject">Votre sujet ici</span><br><br>
            <span id="previewContent">Votre contenu ici</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const subjectInput = document.getElementById('sujet');
        const contentInput = document.getElementById('contenu');
        const previewSubject = document.getElementById('previewSubject');
        const previewContent = document.getElementById('previewContent');

        function updatePreview() {
            previewSubject.textContent = subjectInput.value || 'Votre sujet ici';
            previewContent.textContent = contentInput.value || 'Votre contenu ici';
        }

        if (subjectInput && contentInput) {
            subjectInput.addEventListener('input', updatePreview);
            contentInput.addEventListener('input', updatePreview);
        }
    });
</script>
@endpush

@endsection
