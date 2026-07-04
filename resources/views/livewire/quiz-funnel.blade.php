<div class="mx-auto max-w-2xl">
    @if($session && $session->status->value === 'completed')
        <div class="rounded-3xl border border-emerald-500/20 bg-emerald-500/10 p-8">
            <p class="text-sm font-medium uppercase tracking-wider text-emerald-300">Assessment complete</p>
            <h1 class="mt-3 text-3xl font-bold text-white">{{ $session->outcome_label }}</h1>
            @if($session->outcomeQuestion?->outcome_summary)
                <p class="mt-4 text-slate-200">{{ $session->outcomeQuestion->outcome_summary }}</p>
            @endif

            @php($product = $this->recommendedProduct())
            @if($product)
                <div class="mt-8 rounded-2xl border border-white/10 bg-slate-900/60 p-6">
                    <p class="text-sm text-slate-400">Recommended next step</p>
                    <h2 class="mt-1 text-xl font-semibold text-white">{{ $product->name }}</h2>
                    <p class="mt-2 text-slate-300">{{ $product->description }}</p>
                    <p class="mt-4 text-2xl font-bold text-indigo-300">${{ number_format($product->price_cents / 100, 2) }}</p>
                    <a href="{{ route('checkout.show', $product->slug) }}?session={{ $session->id }}"
                       class="mt-6 inline-flex rounded-xl bg-indigo-500 px-5 py-3 font-semibold text-white hover:bg-indigo-400">
                        Continue to checkout
                    </a>
                </div>
            @endif
        </div>
    @elseif($currentQuestion)
        <div class="mb-6">
            <div class="mb-2 flex items-center justify-between text-sm text-slate-400">
                <span>{{ $quiz->title }}</span>
                <span>{{ $progress }}% complete</span>
            </div>
            <div class="h-2 overflow-hidden rounded-full bg-white/10">
                <div class="h-full rounded-full bg-indigo-500 transition-all duration-500" style="width: {{ $progress }}%"></div>
            </div>
        </div>

        <form wire:submit="submitAnswer" class="rounded-3xl border border-white/10 bg-slate-900/70 p-8 shadow-xl">
            <h1 class="text-2xl font-bold text-white">{{ $currentQuestion->prompt }}</h1>
            @if($currentQuestion->help_text)
                <p class="mt-2 text-slate-400">{{ $currentQuestion->help_text }}</p>
            @endif

            <div class="mt-8 space-y-3">
                @foreach($currentQuestion->options as $option)
                    <label class="flex cursor-pointer items-center gap-4 rounded-2xl border border-white/10 px-4 py-4 transition hover:border-indigo-400/40 hover:bg-indigo-500/5">
                        <input type="radio" wire:model="selectedOptionId" value="{{ $option->id }}" class="text-indigo-500 focus:ring-indigo-400">
                        <span class="text-slate-100">{{ $option->label }}</span>
                    </label>
                @endforeach
            </div>

            @error('selectedOptionId')
                <p class="mt-4 text-sm text-rose-400">{{ $message }}</p>
            @enderror

            <button type="submit"
                    class="mt-8 w-full rounded-xl bg-indigo-500 px-5 py-3 font-semibold text-white hover:bg-indigo-400 disabled:opacity-50"
                    @disabled(!$selectedOptionId)>
                Continue
            </button>
        </form>
    @endif
</div>
