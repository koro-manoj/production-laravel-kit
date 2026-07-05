<x-filament-panels::page.simple>
    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}
            {{ $this->registerAction }}
        </x-slot>
    @endif

    <div class="nl-login-brand">
        <div class="nl-login-brand-row">
            <span class="nl-login-mark" aria-hidden="true">N</span>
            <span class="nl-login-name">Northline</span>
        </div>
        <span class="nl-login-badge">Admin console</span>
    </div>

    <div class="nl-login-intro">
        <h1 class="nl-login-heading">Welcome back</h1>
        <p class="nl-login-subheading">Sign in to manage products, orders, and integrations.</p>
    </div>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
</x-filament-panels::page.simple>
