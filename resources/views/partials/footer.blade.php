<footer class="border-t border-ink/8 bg-cream">
    <div class="mx-auto max-w-7xl px-6 py-14 lg:px-10">
        <div class="grid gap-10 lg:grid-cols-[1.2fr_1fr_1fr]">
            <div>
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-moss text-sm font-bold text-white">K</span>
                    <span class="font-display text-xl font-semibold text-ink">Koro Kit</span>
                </div>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-ink-muted">
                    Modular Laravel telehealth stack — branching assessments, Stripe checkout, encrypted integrations, and role-based operations.
                </p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.15em] text-ink-faint">Platform</p>
                <ul class="mt-4 space-y-3 text-sm">
                    <li><a href="{{ route('products.index') }}" class="text-ink-muted transition hover:text-moss">Care packages</a></li>
                    @if($q = \App\Domain\Quiz\Models\Quiz::query()->where('is_active', true)->first())
                        <li><a href="{{ route('quiz.show', $q) }}" class="text-ink-muted transition hover:text-moss">Health assessment</a></li>
                    @endif
                    <li><a href="/admin" class="text-ink-muted transition hover:text-moss">Admin console</a></li>
                </ul>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.15em] text-ink-faint">Stack</p>
                <ul class="mt-4 space-y-3 text-sm text-ink-muted">
                    <li>Laravel 11 · Livewire · Filament</li>
                    <li>Stripe · Sanctum · Horizon</li>
                    <li>DB-encrypted provider keys</li>
                </ul>
            </div>
        </div>
        <div class="mt-12 flex flex-col items-start justify-between gap-4 border-t border-ink/8 pt-8 text-sm text-ink-faint sm:flex-row sm:items-center">
            <p>&copy; {{ date('Y') }} Koro Kit</p>
            <a href="https://github.com/koro-manoj/production-laravel-kit" class="transition hover:text-moss" target="_blank" rel="noopener">View source on GitHub</a>
        </div>
    </div>
</footer>
