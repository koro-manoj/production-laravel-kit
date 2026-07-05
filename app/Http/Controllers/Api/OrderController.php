<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Payments\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = Order::query()
            ->with('product')
            ->when(
                $request->user()->hasRole('Patient'),
                fn ($query) => $query->where('user_id', $request->user()->id)
            )
            ->latest()
            ->paginate(20);

        return response()->json($orders);
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        if ($request->user()->hasRole('Patient') && $order->user_id !== $request->user()->id) {
            abort(403);
        }

        $order->load(['product', 'payments', 'quizSession']);

        return response()->json($order);
    }
}
