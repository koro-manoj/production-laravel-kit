<div class="mx-auto max-w-2xl">
    @if($session && $session->status->value === 'completed')
        <div class="card-elevated overflow-hidden">
            <div class="border-b border-moss/10 bg-moss/5 px-8 py-6">
                <p class="text-xs font-bold uppercase tracking-widest text-moss">Assessment complete</p>
                <h1 class="mt-2 font-display text-3xl font-semibold text-ink">{{ $session->outcome_label }}</h1>
            </div>
            <div class="px-8 py-6">
                @if($session->outcomeQuestion?->outcome_summary)
                    <p class="text-lg leading-relaxed text-ink-muted">{{ $session->outcomeQuestion->outcome_summary }}</p>
                @endif

                @php($product = $this->recommendedProduct())
                @if($product)
                    <div class="mt-8 rounded-xl border border-ink/8 bg-paper/60 p-6">
                        <p class="text-xs font-semibold uppercase tracking-widest text-ink-faint">Recommended next step</p>
                        <h2 class="mt-2 font-display text-xl font-semibold text-ink">{{ $product->name }}</h2>
                        <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ $product->description }}</p>
                        <p class="mt-4 font-display text-3xl font-semibold text-moss">${{ number_format($product->price_cents / 100, 2) }}</p>
                        <a href="{{ route('checkout.show', $product->slug) }}?session={{ $session->id }}"
                           class="btn-primary mt-6">
                            Continue to checkout
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @elseif($currentQuestion)
        <div class="mb-8">
            <div class="mb-3 flex items-center justify-between text-sm">
                <span class="font-medium text-ink-muted">{{ $quiz->title }}</span>
                <span class="tabular-nums text-ink-faint">{{ $progress }}%</span>
            </div>
            <div class="progress-track">
                <div class="progress-fill" style="width: {{ $progress }}%"></div>
            </div>
        </div>

        <form wire:submit="submitAnswer" class="card-elevated p-8">
            <h1 class="font-display text-2xl font-semibold leading-snug text-ink">{{ $currentQuestion->prompt }}</h1>
            @if($currentQuestion->help_text)
                <p class="mt-3 text-ink-muted">{{ $currentQuestion->help_text }}</p>
            @endif

            <div class="mt-8 space-y-3">
                @foreach($currentQuestion->options as $option)
                    <label class="option-card">
                        <input type="radio" wire:model="selectedOptionId" value="{{ $option->id }}" class="h-4 w-4 border-ink/20 text-moss focus:ring-moss/30">
                        <span class="text-ink">{{ $option->label }}</span>
                    </label>
                @endforeach
            </div>

            @error('selectedOptionId')
                <p class="mt-4 text-sm text-clay">{{ $message }}</p>
            @enderror

            <button type="submit"
                    class="btn-primary mt-8 w-full disabled:cursor-not-allowed disabled:opacity-40"
                    @disabled(!$selectedOptionId)>
                Continue
            </button>
        </form>
    @endif
</div>
