@extends('layouts.app')

@section('title', 'Payment successful')

@section('content')
    <div class="mx-auto max-w-lg rounded-3xl border border-emerald-500/20 bg-emerald-500/10 p-8 text-center">
        <h1 class="text-3xl font-bold text-white">Thank you</h1>
        <p class="mt-3 text-slate-200">Order {{ $order->reference }} is {{ $order->status }}.</p>
        <p class="mt-2 text-slate-400">Amount: ${{ number_format($order->amount_cents / 100, 2) }} {{ $order->currency }}</p>
        <a href="{{ route('home') }}" class="mt-8 inline-flex rounded-xl bg-white/10 px-5 py-3 font-semibold text-white hover:bg-white/15">
            Back to home
        </a>
    </div>
@endsection
