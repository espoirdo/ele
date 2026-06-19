@props(['event'])

<a href="{{ route('events.show', $event) }}"
   class="event-card-top">
    <div class="event-card-top-image">
        <img src="{{ $event->image_url }}" alt="{{ $event->titre }}">
        <div class="event-card-top-overlay"></div>
        @if($event->premium_mise_en_avant)
            <span class="event-card-top-badge">Premium</span>
        @endif
        <div class="event-card-top-info">
            <p class="event-card-top-title">{{ $event->titre }}</p>
            <p class="event-card-top-date">
                {{ $event->date->format('l, d M') }} / {{ $event->heure }}
            </p>
            <p class="event-card-top-lieu">{{ $event->lieu }}</p>
        </div>
    </div>
    <div class="event-card-top-body">
        <p class="event-card-top-desc">{{ Str::limit($event->description, 100) }}</p>
    </div>
</a>

<style>
.event-card-top {
    display: block;
    border-radius: 12px;
    overflow: hidden;
    background: white;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
    text-decoration: none;
    transition: all 0.25s ease;
}

.event-card-top:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
}

.event-card-top-image {
    position: relative;
    height: 224px;
    overflow: hidden;
}

.event-card-top-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.event-card-top:hover .event-card-top-image img {
    transform: scale(1.08);
}

.event-card-top-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, transparent 60%);
}

.event-card-top-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: linear-gradient(135deg, #CC0000, #910000);
    color: white;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 40px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.event-card-top-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 16px;
}

.event-card-top-title {
    color: white;
    font-weight: 700;
    font-size: 18px;
    margin: 0 0 8px 0;
    line-height: 1.3;
}

.event-card-top-date {
    color: #F4A0A0;
    font-size: 13px;
    margin: 0 0 4px 0;
}

.event-card-top-lieu {
    color: rgba(255, 255, 255, 0.78);
    font-size: 13px;
    margin: 0;
}

.event-card-top-body {
    padding: 16px;
}

.event-card-top-desc {
    color: #666;
    font-size: 13px;
    line-height: 1.5;
    margin: 0;
}
</style>