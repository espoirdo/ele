<div class="progress-bar-container">
    <div class="progress-bar">
        @php
            $steps = [
                1 => 'Information',
                2 => 'Lieu et date',
                3 => 'Billetterie',
                4 => 'Medias et publication'
            ];
        @endphp

        @foreach($steps as $step => $label)
            <div class="progress-step {{ $currentStep >= $step ? 'active' : '' }} {{ $currentStep > $step ? 'completed' : '' }}">
                <div class="step-circle">
                    @if($currentStep > $step)
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    @else
                        {{ $step }}
                    @endif
                </div>
                <span class="step-label">{{ $label }}</span>
            </div>
            @if($step < 4)
                <div class="progress-line {{ $currentStep > $step ? 'active' : '' }}"></div>
            @endif
        @endforeach
    </div>
</div>

<style>
.progress-bar-container {
    margin-bottom: 40px;
}

.progress-bar {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    gap: 0;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 1;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #E0E0E0;
    color: #888;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.progress-step.active .step-circle {
    background: #CC0000;
    color: white;
}

.progress-step.completed .step-circle {
    background: #CC0000;
    color: white;
}

.step-label {
    margin-top: 8px;
    font-family: 'Poppins', sans-serif;
    font-size: 11px;
    color: #888;
    text-align: center;
    white-space: nowrap;
}

.progress-step.active .step-label {
    color: #CC0000;
    font-weight: 600;
}

.progress-line {
    flex: 1;
    height: 3px;
    background: #E0E0E0;
    margin-top: 18px;
    min-width: 60px;
    max-width: 100px;
    transition: background 0.3s ease;
}

.progress-line.active {
    background: #CC0000;
}

@media (max-width: 600px) {
    .step-label {
        display: none;
    }

    .progress-line {
        min-width: 40px;
        margin-top: 18px;
    }

    .step-circle {
        width: 36px;
        height: 36px;
        font-size: 13px;
    }
}
</style>