@props(['event'])

<article class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition group cursor-pointer">

    {{-- Image --}}
    <a href="{{ route('events.show', $event->slug) }}">
        <div class="relative h-40 overflow-hidden">
            @if($event->image_couverture)
                <img src="{{ Storage::url($event->image_couverture) }}"
                     alt="{{ $event->titre }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            @else
                <div class="w-full h-full bg-gradient-to-br from-red-100 to-red-200 flex items-center justify-center">
                    <span class="text-[#8B1A1A] text-2xl font-bold opacity-30">ELODJI</span>
                </div>
            @endif

            {{-- Badge catégorie --}}
            <span class="absolute top-2 left-2 bg-[#8B1A1A] text-white text-xs px-2 py-1 rounded-full">
                {{ $event->category->name ?? 'Événement' }}
            </span>
        </div>
    </a>

    {{-- Contenu --}}
    <div class="p-3">
        <h3 class="font-bold text-gray-800 text-sm truncate">{{ $event->titre }}</h3>

        <div class="flex items-center gap-1 mt-1 text-gray-500 text-xs">
            {{-- Icône calendrier --}}
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>{{ \Carbon\Carbon::parse($event->date_heure)->translatedFormat('l d F · H\hi') }}</span>
        </div>

        <div class="flex items-center gap-1 mt-1 text-gray-500 text-xs">
            {{-- Icône lieu --}}
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            </svg>
            <span class="truncate">{{ $event->lieu }}</span>
        </div>

        <div class="flex items-center justify-between mt-3">
            <span class="text-[#8B1A1A] font-bold text-sm">
                @if($event->prix > 0)
                    {{ number_format($event->prix, 0, ',', ' ') }} FCFA
                @else
                    Gratuit
                @endif
            </span>
            <a href="{{ route('events.show', $event->slug) }}"
               class="text-xs text-[#8B1A1A] hover:underline font-medium">
                Voir plus →
            </a>
        </div>
    </div>
</article>