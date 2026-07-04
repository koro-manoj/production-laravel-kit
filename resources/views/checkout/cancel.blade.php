@extends('layouts.app')

@section('title', 'Checkout cancelled')

@section('content')
    <div class="mx-auto max-w-lg rounded-3xl border border-white/10 bg-slate-900/70 p-8 text-center">
        <h1 class="text-3xl font-bold text-white">Checkout cancelled</h1>
        <p class="mt-3 text-slate-300">Order {{ $order->reference }} was not completed.</p>
        <a href="{{ route('home') }}" class="mt-8 inline-flex rounded-xl bg-indigo-500 px-5 py-3 font-semibold text-white hover:bg-indigo-400">
            Return home
        </a>
    </div>
@endsection
