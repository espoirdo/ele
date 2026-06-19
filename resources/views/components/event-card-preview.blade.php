<div class="rounded-[28px] border border-gray-200 bg-white p-6 shadow-sm">
    <div class="mb-5 h-56 overflow-hidden rounded-3xl bg-gray-100">
        <template x-if="event.imagePreview">
            <img :src="event.imagePreview" alt="Aperçu de l'image" class="h-full w-full object-cover" />
        </template>
        <template x-if="!event.imagePreview">
            <div class="flex h-full items-center justify-center text-gray-400">Aperçu image de couverture</div>
        </template>
    </div>

    <h2 class="text-2xl font-bold text-gray-900" x-text="event.titre || 'Titre de l’événement'">Titre de l’événement</h2>
    <p class="mt-3 text-sm leading-6 text-gray-600" x-text="event.description || 'Résumé descriptif de l’événement.'">Résumé descriptif de l’événement.</p>

    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        <div class="rounded-3xl bg-red-50 p-4">
            <p class="text-xs uppercase tracking-[0.24em] text-red-700">Date</p>
            <p class="mt-2 font-semibold text-gray-900" x-text="event.dateLabel || 'À définir'"></p>
        </div>
        <div class="rounded-3xl bg-red-50 p-4">
            <p class="text-xs uppercase tracking-[0.24em] text-red-700">Lieu</p>
            <p class="mt-2 font-semibold text-gray-900" x-text="event.lieu || 'Lieu de l’événement'"></p>
        </div>
    </div>

    <div class="mt-6 rounded-3xl border border-gray-200 p-4">
        <p class="text-xs uppercase tracking-[0.24em] text-gray-500">Billets ajoutés</p>
        <template x-if="event.tickets.length">
            <div class="mt-3 space-y-3">
                <template x-for="ticket in event.tickets" :key="ticket.nom">
                    <div class="rounded-3xl bg-gray-50 p-4">
                        <div class="flex items-center justify-between gap-4">
                            <p class="font-semibold text-gray-900" x-text="ticket.nom"></p>
                            <p class="text-sm text-gray-600" x-text="ticket.quantite + ' places'"></p>
                        </div>
                        <p class="mt-2 text-sm text-gray-500" x-text="formattedPrice(ticket.prix)"></p>
                    </div>
                </template>
            </div>
        </template>
        <template x-if="!event.tickets.length">
            <p class="mt-3 text-sm text-gray-500">Aucun billet configuré pour le moment.</p>
        </template>
    </div>
</div>
