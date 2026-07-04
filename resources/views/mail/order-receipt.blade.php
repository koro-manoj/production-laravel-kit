<x-mail::message>
# Payment receipt

Thank you for your order, **{{ $order->customer_name ?? 'Customer' }}**.

**Reference:** {{ $order->reference }}  
**Product:** {{ $order->product->name }}  
**Amount:** ${{ number_format($order->amount_cents / 100, 2) }} {{ strtoupper($order->currency) }}  
**Status:** {{ ucfirst($order->status) }}

@if($order->quiz_session_id)
This purchase followed your health assessment quiz session.
@endif

<x-mail::button :url="config('app.url')">
Return to {{ config('app.name') }}
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
