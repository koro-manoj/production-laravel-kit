<div class="mx-auto max-w-2xl animate-fade-up">
    @if($session && $session->status->value === 'completed')
        <div class="card-elevated overflow-hidden">
            <div class="bg-gradient-to-br from-moss to-moss-light px-8 py-8 text-white">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-white/70">Complete</p>
                <h1 class="mt-3 font-display text-3xl font-semibold">{{ $session->outcome_label }}</h1>
            </div>
            <div class="px-8 py-8">
                @if($session->outcomeQuestion?->outcome_summary)
                    <p class="text-lg leading-relaxed text-ink-muted">{{ $session->outcomeQuestion->outcome_summary }}</p>
                @endif

                @php($product = $this->recommendedProduct())
                @if($product)
                    <div class="mt-8 rounded-2xl border border-moss/15 bg-cream p-6">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-moss">Your recommended package</p>
                        <h2 class="mt-2 font-display text-2xl font-semibold text-ink">{{ $product->name }}</h2>
                        <p class="mt-2 text-sm text-ink-muted">{{ $product->description }}</p>
                        <p class="mt-5 font-display text-4xl font-semibold text-moss">${{ number_format($product->price_cents / 100, 2) }}</p>
                        <a href="{{ route('checkout.show', $product->slug) }}?session={{ $session->id }}" class="btn-primary mt-6 w-full">
                            Continue to checkout
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @elseif($currentQuestion)
        <div class="mb-8">
            <div class="mb-4 flex items-center justify-between">
                <a href="{{ route('home') }}" class="text-sm text-ink-faint transition hover:text-moss">← Exit</a>
                <span class="rounded-full bg-moss/10 px-3 py-1 text-xs font-semibold text-moss">{{ $progress }}%</span>
            </div>
            <div class="progress-track">
                <div class="progress-fill" style="width: {{ $progress }}%"></div>
            </div>
            <p class="mt-2 text-sm text-ink-faint">{{ $quiz->title }}</p>
        </div>

        <form wire:submit="submitAnswer" class="card-elevated p-8">
            <p class="text-[11px] font-bold uppercase tracking-wider text-clay">Question</p>
            <h1 class="mt-3 font-display text-2xl font-semibold leading-snug text-ink sm:text-3xl">{{ $currentQuestion->prompt }}</h1>
            @if($currentQuestion->help_text)
                <p class="mt-3 text-ink-muted">{{ $currentQuestion->help_text }}</p>
            @endif

            <div class="mt-8 space-y-3">
                @foreach($currentQuestion->options as $option)
                    <label class="option-card">
                        <input type="radio" wire:model="selectedOptionId" value="{{ $option->id }}" class="h-4 w-4 border-ink/20 text-moss focus:ring-moss/30">
                        <span class="font-medium text-ink">{{ $option->label }}</span>
                    </label>
                @endforeach
            </div>

            @error('selectedOptionId')
                <p class="mt-4 text-sm text-clay">{{ $message }}</p>
            @enderror

            <button type="submit" class="btn-primary mt-8 w-full disabled:opacity-40" @disabled(!$selectedOptionId)>
                Continue
            </button>
        </form>
    @endif
</div>
