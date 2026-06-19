@extends('layouts.app')

@section('title', 'Creer un evenement - Etape 3 sur 4 - ELEDJI')

@section('content')
<div class="create-event-page" x-data="ticketManager()">
    <div class="create-event-container">
        {{-- Progress Bar --}}
        @include('events.create.progress-bar', ['currentStep' => 3])

        {{-- Header --}}
        <div class="create-event-header">
            <h1>Creer un evenement - Etape 3 sur 4</h1>
            <p>Billetterie et tarification</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('events.create.step3.post') }}" method="POST" class="create-event-form">
            @csrf

            <div class="form-card">
                <h3 class="card-title">Type d'acces</h3>

                <div class="pricing-options">
                    <button type="button"
                            class="pricing-card"
                            :class="isGratuit ? 'selected' : ''"
                            @click="setGratuit(true)">
                        <div class="pricing-icon">
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="pricing-info">
                            <span class="pricing-name">Gratuit</span>
                            <span class="pricing-desc">Entree libre sans billet</span>
                        </div>
                        <input type="radio" name="est_gratuit" value="1" x-model="estGratuit">
                    </button>

                    <button type="button"
                            class="pricing-card"
                            :class="!isGratuit ? 'selected' : ''"
                            @click="setGratuit(false)">
                        <div class="pricing-icon">
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                        <div class="pricing-info">
                            <span class="pricing-name">Payant</span>
                            <span class="pricing-desc">Vente de billets</span>
                        </div>
                        <input type="radio" name="est_gratuit" value="0" x-model="estGratuit">
                    </button>
                </div>

                <div class="form-group" x-show="!isGratuit" x-transition>
                    <label for="prix">Prix d'entree (FCFA)</label>
                    <input type="number"
                           id="prix"
                           name="prix"
                           value="{{ old('prix', $data['prix'] ?? '') }}"
                           min="0"
                           step="100"
                           placeholder="0">
                    <small class="form-hint">Laissez 0 pour tarif minimal</small>
                </div>

                <div class="form-group">
                    <label for="nombre_places">Nombre de places</label>
                    <input type="number"
                           id="nombre_places"
                           name="nombre_places"
                           value="{{ old('nombre_places', $data['nombre_places'] ?? '') }}"
                           required
                           min="1"
                           placeholder="Nombre de places disponibles">
                </div>

                <div class="form-group" x-show="!isGratuit" x-transition>
                    <div class="tickets-header">
                        <label>Types de billets</label>
                        <button type="button"
                                class="add-ticket-btn"
                                @click="addTicket()"
                                x-show="tickets.length < 5"
                                :disabled="tickets.length >= 5">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Ajouter
                        </button>
                    </div>

                    <template x-for="(ticket, index) in tickets" :key="index">
                        <div class="ticket-item">
                            <div class="ticket-field">
                                <input type="text"
                                       :name="`tickets[${index}][nom]`"
                                       x-model="ticket.nom"
                                       placeholder="Nom du billet"
                                       required>
                            </div>
                            <div class="ticket-field">
                                <input type="number"
                                       :name="`tickets[${index}][prix]`"
                                       x-model="ticket.prix"
                                       min="0"
                                       step="100"
                                       placeholder="Prix (FCFA)"
                                       required>
                            </div>
                            <div class="ticket-field">
                                <input type="number"
                                       :name="`tickets[${index}][quantite]`"
                                       x-model="ticket.quantite"
                                       min="1"
                                       placeholder="Qte"
                                       required>
                            </div>
                            <button type="button"
                                    class="remove-ticket-btn"
                                    @click="removeTicket(index)"
                                    x-show="tickets.length > 1">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                    <small class="form-hint">Maximum 5 types de billets</small>
                </div>
            </div>

            {{-- Navigation --}}
            <div class="form-navigation">
                <a href="{{ route('events.create.step2') }}" class="btn btn-secondary btn-prev">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Precedent
                </a>
                <button type="submit" class="btn btn-primary btn-next">
                    Suivant
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.create-event-page {
    min-height: calc(100vh - 200px);
    padding: 120px 24px 60px;
    background: #F9F9F9;
}

.create-event-container {
    max-width: 720px;
    margin: 0 auto;
}

.create-event-header {
    text-align: center;
    margin-bottom: 40px;
}

.create-event-header h1 {
    font-family: 'Eras Medium ITC', serif;
    font-size: 28px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 8px;
}

.create-event-header p {
    font-size: 14px;
    color: #666;
}

.form-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
    padding: 32px;
    margin-bottom: 24px;
}

.card-title {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 16px;
}

.pricing-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 24px;
}

.pricing-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border: 2px solid #E0E0E0;
    border-radius: 12px;
    background: white;
    cursor: pointer;
    transition: all 0.25s ease;
    text-align: left;
    position: relative;
}

.pricing-card:hover {
    border-color: #CC0000;
}

.pricing-card.selected {
    border-color: #CC0000;
    background: rgba(204, 0, 0, 0.05);
}

.pricing-card input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.pricing-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: #F5F5F5;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
}

.pricing-card.selected .pricing-icon {
    background: #CC0000;
    color: white;
}

.pricing-info {
    display: flex;
    flex-direction: column;
}

.pricing-name {
    font-weight: 600;
    font-size: 14px;
    color: #1a1a1a;
}

.pricing-desc {
    font-size: 12px;
    color: #666;
}

.form-group {
    margin-bottom: 24px;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    font-weight: 500;
    color: #444444;
    margin-bottom: 6px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #E0E0E0;
    border-radius: 8px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    color: #1a1a1a;
    background: white;
    transition: all 0.25s ease;
    outline: none;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #CC0000;
    box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.08);
}

.form-group input::placeholder {
    color: #999;
}

.form-hint {
    display: block;
    font-size: 11px;
    color: #888;
    margin-top: 4px;
}

.tickets-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.add-ticket-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: rgba(204, 0, 0, 0.1);
    color: #CC0000;
    border: none;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
}

.add-ticket-btn:hover {
    background: rgba(204, 0, 0, 0.2);
}

.ticket-item {
    display: grid;
    grid-template-columns: 1fr 100px 80px 40px;
    gap: 10px;
    margin-bottom: 10px;
    padding: 12px;
    background: #F9F9F9;
    border-radius: 8px;
}

.ticket-field input {
    width: 100%;
    padding: 10px 12px;
    border: 1.5px solid #E0E0E0;
    border-radius: 8px;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    outline: none;
    transition: all 0.25s ease;
}

.ticket-field input:focus {
    border-color: #CC0000;
}

.remove-ticket-btn {
    width: 40px;
    height: 40px;
    border: none;
    background: #FEE2E2;
    color: #CC0000;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
}

.remove-ticket-btn:hover {
    background: #CC0000;
    color: white;
}

.error-message {
    display: block;
    font-size: 12px;
    color: #CC0000;
    margin-top: 6px;
}

.form-navigation {
    display: flex;
    justify-content: space-between;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 28px;
    border-radius: 40px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
    text-decoration: none;
    border: none;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}

.btn-primary {
    background: linear-gradient(135deg, #CC0000, #910000);
    color: white;
    min-width: 160px;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(204, 0, 0, 0.35);
}

.btn-secondary {
    background: white;
    color: #555;
    border: 1.5px solid #E0E0E0;
}

.btn-secondary:hover {
    background: #F5F5F5;
}

.btn-prev {
    min-width: 140px;
}

.btn-next {
    min-width: 160px;
}

@media (max-width: 600px) {
    .form-card {
        padding: 24px 20px;
    }

    .pricing-options {
        grid-template-columns: 1fr;
    }

    .ticket-item {
        grid-template-columns: 1fr;
        gap: 8px;
    }

    .remove-ticket-btn {
        width: 100%;
    }

    .create-event-header h1 {
        font-size: 22px;
    }

    .form-navigation {
        flex-direction: column-reverse;
        gap: 12px;
    }

    .btn {
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
function ticketManager() {
    var defaultTickets = [{nom: 'Entree generale', prix: 0, quantite: 50}];
    var savedTickets = {{ json_encode(old('tickets', $data['tickets'] ?? [])) }};

    return {
        estGratuit: '{{ old('est_gratuit', $data['est_gratuit'] ?? '1') }}',
        tickets: savedTickets.length > 0 ? savedTickets : defaultTickets,

        get isGratuit() {
            return this.estGratuit === '1' || this.estGratuit === true;
        },

        setGratuit(value) {
            this.estGratuit = value ? '1' : '0';
        },

        addTicket() {
            if (this.tickets.length < 5) {
                this.tickets.push({ nom: '', prix: 0, quantite: 1 });
            }
        },

        removeTicket(index) {
            if (this.tickets.length > 1) {
                this.tickets.splice(index, 1);
            }
        }
    };
}
</script>
@endpush
@endsection