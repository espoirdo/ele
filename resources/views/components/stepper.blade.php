@props(['step'])

<div class="rounded-3xl border border-red-100 bg-white p-4 shadow-sm">
    <div class="grid gap-3 sm:grid-cols-3">
        @foreach(['Informations', 'Billets', 'Aperçu'] as $index => $label)
            <div class="flex items-start gap-3 rounded-3xl p-4 transition duration-200 {{ $step === $index + 1 ? 'bg-red-50 border border-red-200' : 'bg-gray-50' }}">
                <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $step === $index + 1 ? 'bg-red-600 text-white' : 'bg-white text-gray-600' }} font-semibold">{{ $index + 1 }}</div>
                <div>
                    <p class="text-sm uppercase tracking-[0.18em] text-gray-500">Étape {{ $index + 1 }}</p>
                    <p class="font-semibold text-sm text-gray-900">{{ $label }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
