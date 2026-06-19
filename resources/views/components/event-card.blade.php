@props(['event'])

<a href="{{ route('events.show', $event) }}"
   class="event-card-component">
    <div class="event-card-image">
        <img src="{{ $event->image_url }}" alt="{{ $event->titre }}">
        <div class="event-card-overlay"></div>
        @if($event->premium_mise_en_avant)
            <span class="event-card-badge">Premium</span>
        @endif
        <div class="event-card-content">
            <p class="event-card-title">{{ $event->titre }}</p>
            <p class="event-card-date">
                {{ $event->date->format('l, d M') }} / {{ $event->heure }}
            </p>
            <p class="event-card-lieu">{{ $event->lieu }}</p>
        </div>
    </div>
</a>

<style>
.event-card-component {
    display: block;
    border-radius: 12px;
    overflow: hidden;
    background: white;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
    text-decoration: none;
    transition: all 0.25s ease;
}

.event-card-component:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.event-card-image {
    position: relative;
    height: 176px;
    overflow: hidden;
}

.event-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.event-card-component:hover .event-card-image img {
    transform: scale(1.08);
}

.event-card-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.75) 0%, transparent 60%);
}

.event-card-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: linear-gradient(135deg, #CC0000, #910000);
    color: white;
    font-size: 10px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 40px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.event-card-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 14px;
}

.event-card-title {
    color: white;
    font-weight: 700;
    font-size: 14px;
    margin: 0 0 4px 0;
    line-height: 1.3;
}

.event-card-date {
    color: #F4A0A0;
    font-size: 12px;
    margin: 0 0 2px 0;
}

.event-card-lieu {
    color: rgba(255, 255, 255, 0.78);
    font-size: 12px;
    margin: 0;
}
</style>