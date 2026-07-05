@extends('layouts.store')

@section('title', 'Order confirmed — '.config('store.name'))

@section('content')
    <div class="mx-auto max-w-lg px-6 py-12 lg:py-16">
        <div class="card-elevated overflow-hidden text-center animate-fade-up">
            <div class="bg-gradient-to-b from-moss/10 to-transparent px-8 py-10">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-moss text-paper shadow-lift">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h1 class="mt-6 font-display text-3xl font-semibold text-ink">Thank you for your order</h1>
                <p class="mt-2 text-ink-muted">Order <span class="font-mono text-sm text-ink">{{ $order->reference }}</span></p>
            </div>
            <div class="border-t border-ink/6 px-8 py-7 text-left">
                @if($order->items->isNotEmpty())
                    <p class="text-xs font-bold uppercase tracking-widest text-ink-faint">Items</p>
                    <ul class="mt-2 space-y-2">
                        @foreach($order->items as $item)
                            <li class="flex justify-between text-sm">
                                <span class="text-ink">{{ $item->product_name }} × {{ $item->quantity }}</span>
                                <span class="font-medium text-ink">{{ money_format_cents($item->unit_price_cents * $item->quantity) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @elseif($order->product)
                    <p class="text-xs font-bold uppercase tracking-widest text-ink-faint">Product</p>
                    <p class="mt-1 font-semibold text-ink">{{ $order->product->name }}</p>
                @endif

                <p class="mt-4 text-xs font-bold uppercase tracking-widest text-ink-faint">Total</p>
                <p class="mt-1 font-display text-2xl font-semibold text-moss">{{ money_format_cents($order->amount_cents) }}</p>
                <p class="mt-4 text-sm text-ink-muted">
                    Status: <span class="font-medium capitalize text-ink">{{ $order->status }}</span>.
                    A receipt email is queued if mail is configured.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('home') }}" class="btn-primary">Back to home</a>
                    <a href="{{ route('shop.index') }}" class="btn-outline">Continue shopping</a>
                </div>
            </div>
        </div>
    </div>
@endsection
