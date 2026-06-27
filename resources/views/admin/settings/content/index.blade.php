@extends('admin.layouts.app')

@section('title', 'Contenu du site')

@section('content')
<div class="page-header">
    <h1 class="page-title">Contenu du site</h1>
</div>

<form method="POST" action="{{ route('admin.settings.content.update') }}">
    @csrf

    {{-- Navigation par onglets --}}
    <div class="settings-tabs">
        <button type="button" class="tab-btn active" data-tab="home">
            <i class="fas fa-home"></i> Accueil
        </button>
        <button type="button" class="tab-btn" data-tab="events">
            <i class="fas fa-calendar"></i> Evenements
        </button>
        <button type="button" class="tab-btn" data-tab="event-detail">
            <i class="fas fa-calendar-check"></i> Detail evenement
        </button>
        <button type="button" class="tab-btn" data-tab="footer">
            <i class="fas fa-footer"></i> Footer
        </button>
        <button type="button" class="tab-btn" data-tab="auth">
            <i class="fas fa-sign-in-alt"></i> Authentification
        </button>
    </div>

    {{-- TAB: ACCUEIL --}}
    <div class="tab-content active" id="tab-home">
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 20px; color: #1A1A1A;">
                <i class="fas fa-home" style="color: #CC0000; margin-right: 8px;"></i>
                Hero - Titre principal
            </h3>

            <div class="form-group">
                <label class="form-label">Ligne 1 du titre</label>
                <input type="text" name="home_hero_title_line1" class="form-input"
                       value="{{ setting('home_hero_title_line1', $defaults['home_hero_title_line1']) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Ligne 2 du titre</label>
                <input type="text" name="home_hero_title_line2" class="form-input"
                       value="{{ setting('home_hero_title_line2', $defaults['home_hero_title_line2']) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Sous-titre (ligne 3)</label>
                <input type="text" name="home_hero_subtitle" class="form-input"
                       value="{{ setting('home_hero_subtitle', $defaults['home_hero_subtitle']) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Placeholder de recherche</label>
                <input type="text" name="home_search_placeholder" class="form-input"
                       value="{{ setting('home_search_placeholder', $defaults['home_search_placeholder']) }}">
            </div>
        </div>

        <div class="card" style="margin-top: 20px;">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 20px; color: #1A1A1A;">
                <i class="fas fa-star" style="color: #CC0000; margin-right: 8px;"></i>
                Section mise en avant
            </h3>

            <div class="form-group">
                <label class="form-label">Label</label>
                <input type="text" name="home_featured_label" class="form-input"
                       value="{{ setting('home_featured_label', $defaults['home_featured_label']) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Titre</label>
                <input type="text" name="home_featured_title" class="form-input"
                       value="{{ setting('home_featured_title', $defaults['home_featured_title']) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Libelle bouton "Voir plus"</label>
                <input type="text" name="home_voir_plus_label" class="form-input"
                       value="{{ setting('home_voir_plus_label', $defaults['home_voir_plus_label']) }}">
            </div>
        </div>

        <div class="card" style="margin-top: 20px;">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 20px; color: #1A1A1A;">
                <i class="fas fa-chart-line" style="color: #CC0000; margin-right: 8px;"></i>
                Statistiques
            </h3>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Label Evenements</label>
                    <input type="text" name="home_stats_events_label" class="form-input"
                           value="{{ setting('home_stats_events_label', $defaults['home_stats_events_label']) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Label Categories</label>
                    <input type="text" name="home_stats_categories_label" class="form-input"
                           value="{{ setting('home_stats_categories_label', $defaults['home_stats_categories_label']) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Label Participants</label>
                    <input type="text" name="home_stats_participants_label" class="form-input"
                           value="{{ setting('home_stats_participants_label', $defaults['home_stats_participants_label']) }}">
                </div>
            </div>
            <p style="font-size: 12px; color: #6B7280; margin-top: 8px;">
                <i class="fas fa-info-circle"></i> Les chiffres des statistiques sont generes automatiquement a partir des donnees du site.
            </p>
        </div>
    </div>

    {{-- TAB: EVENEMENTS LIST --}}
    <div class="tab-content" id="tab-events">
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 20px; color: #1A1A1A;">
                <i class="fas fa-list" style="color: #CC0000; margin-right: 8px;"></i>
                Liste des evenements
            </h3>

            <div class="form-group">
                <label class="form-label">Titre de la page</label>
                <input type="text" name="events_list_title" class="form-input"
                       value="{{ setting('events_list_title', $defaults['events_list_title']) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Texte d'introduction</label>
                <textarea name="events_list_intro" class="form-input" rows="3">{{ setting('events_list_intro', $defaults['events_list_intro']) }}</textarea>
            </div>
        </div>
    </div>

    {{-- TAB: EVENT DETAIL --}}
    <div class="tab-content" id="tab-event-detail">
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 20px; color: #1A1A1A;">
                <i class="fas fa-info-circle" style="color: #CC0000; margin-right: 8px;"></i>
                Page detail evenement
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Bouton "Participer"</label>
                    <input type="text" name="event_participate_label" class="form-input"
                           value="{{ setting('event_participate_label', $defaults['event_participate_label']) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Bouton "Acheter maintenant"</label>
                    <input type="text" name="event_buy_now_label" class="form-input"
                           value="{{ setting('event_buy_now_label', $defaults['event_buy_now_label']) }}">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Message "Evenement complet"</label>
                    <input type="text" name="event_complete_label" class="form-input"
                           value="{{ setting('event_complete_label', $defaults['event_complete_label']) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Label "Complet"</label>
                    <input type="text" name="event_full_label" class="form-input"
                           value="{{ setting('event_full_label', $defaults['event_full_label']) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Label "Gratuit"</label>
                    <input type="text" name="event_free_label" class="form-input"
                           value="{{ setting('event_free_label', $defaults['event_free_label']) }}">
                </div>
            </div>
        </div>
    </div>

    {{-- TAB: FOOTER --}}
    <div class="tab-content" id="tab-footer">
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 20px; color: #1A1A1A;">
                <i class="fas fa-sitemap" style="color: #CC0000; margin-right: 8px;"></i>
                Footer
            </h3>

            <div class="form-group">
                <label class="form-label">Titre Newsletter</label>
                <input type="text" name="footer_newsletter_title" class="form-input"
                       value="{{ setting('footer_newsletter_title', $defaults['footer_newsletter_title']) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Texte Newsletter</label>
                <textarea name="footer_newsletter_text" class="form-input" rows="2">{{ setting('footer_newsletter_text', $defaults['footer_newsletter_text']) }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Placeholder Email</label>
                    <input type="text" name="footer_newsletter_placeholder" class="form-input"
                           value="{{ setting('footer_newsletter_placeholder', $defaults['footer_newsletter_placeholder']) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Bouton S'abonner</label>
                    <input type="text" name="footer_newsletter_button" class="form-input"
                           value="{{ setting('footer_newsletter_button', $defaults['footer_newsletter_button']) }}">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Copyright</label>
                    <input type="text" name="footer_copyright" class="form-input"
                           value="{{ setting('footer_copyright', $defaults['footer_copyright']) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Nom de l'agence</label>
                    <input type="text" name="footer_agency_name" class="form-input"
                           value="{{ setting('footer_agency_name', $defaults['footer_agency_name']) }}">
                </div>
            </div>
        </div>
    </div>

    {{-- TAB: AUTH --}}
    <div class="tab-content" id="tab-auth">
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 20px; color: #1A1A1A;">
                <i class="fas fa-sign-in-alt" style="color: #CC0000; margin-right: 8px;"></i>
                Page de connexion
            </h3>

            <div class="form-group">
                <label class="form-label">Titre de bienvenue</label>
                <input type="text" name="auth_login_title" class="form-input"
                       value="{{ setting('auth_login_title', $defaults['auth_login_title']) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Sous-titre</label>
                <textarea name="auth_login_subtitle" class="form-input" rows="2">{{ setting('auth_login_subtitle', $defaults['auth_login_subtitle']) }}</textarea>
            </div>
        </div>

        <div class="card" style="margin-top: 20px;">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 20px; color: #1A1A1A;">
                <i class="fas fa-user-plus" style="color: #CC0000; margin-right: 8px;"></i>
                Page d'inscription
            </h3>

            <div class="form-group">
                <label class="form-label">Titre de bienvenue</label>
                <input type="text" name="auth_register_title" class="form-input"
                       value="{{ setting('auth_register_title', $defaults['auth_register_title']) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Sous-titre</label>
                <textarea name="auth_register_subtitle" class="form-input" rows="2">{{ setting('auth_register_subtitle', $defaults['auth_register_subtitle']) }}</textarea>
            </div>
        </div>
    </div>

    <div style="margin-top: 24px;">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Enregistrer les modifications
        </button>
    </div>
</form>

@push('styles')
<style>
.settings-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 24px;
    flex-wrap: wrap;
    background: #F3F4F6;
    padding: 6px;
    border-radius: 10px;
}

.tab-btn {
    padding: 10px 20px;
    border: none;
    background: transparent;
    color: #6B7280;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    border-radius: 8px;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.tab-btn:hover {
    color: #1A1A1A;
    background: #E5E7EB;
}

.tab-btn.active {
    background: #CC0000;
    color: white;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            // Add active to clicked
            this.classList.add('active');
            const tabId = this.dataset.tab;
            document.getElementById('tab-' + tabId).classList.add('active');
        });
    });
});
</script>
@endpush
@endsection