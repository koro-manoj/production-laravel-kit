<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Commerce\Models\OrderItem;
use App\Domain\Payments\Models\Order;
use App\Domain\Payments\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        Order::query()
            ->whereIn('reference', ['NL-DEMO001', 'NL-DEMO002', 'NL-DEMO003'])
            ->delete();

        $throw = Product::query()->where('slug', 'merino-wool-throw')->first();
        $mug = Product::query()->where('slug', 'stoneware-mug-set')->first();
        $organizer = Product::query()->where('slug', 'oak-desk-organizer')->first();
        $patient = User::query()->where('email', 'patient@example.com')->first();

        if (! $throw || ! $mug) {
            return;
        }

        $order1 = $this->upsertOrder(
            seedKey: 'sample-multi-item',
            attributes: [
                'user_id' => $patient?->id,
                'product_id' => $throw->id,
                'amount_cents' => $throw->price_cents + $mug->price_cents,
                'currency' => $throw->currency,
                'status' => 'paid',
                'customer_email' => $patient?->email ?? 'customer@example.com',
                'customer_name' => $patient?->name ?? 'Jordan Lee',
                'metadata' => ['source' => 'cart', 'item_count' => 2],
            ],
        );

        OrderItem::query()->updateOrCreate(
            ['order_id' => $order1->id, 'product_id' => $throw->id],
            ['quantity' => 1, 'unit_price_cents' => $throw->price_cents, 'product_name' => $throw->name]
        );
        OrderItem::query()->updateOrCreate(
            ['order_id' => $order1->id, 'product_id' => $mug->id],
            ['quantity' => 1, 'unit_price_cents' => $mug->price_cents, 'product_name' => $mug->name]
        );

        if ($organizer) {
            $order2 = $this->upsertOrder(
                seedKey: 'sample-single-item',
                attributes: [
                    'product_id' => $organizer->id,
                    'amount_cents' => $organizer->price_cents,
                    'currency' => $organizer->currency,
                    'status' => 'paid',
                    'customer_email' => 'sam.chen@example.com',
                    'customer_name' => 'Sam Chen',
                    'metadata' => ['source' => 'cart', 'item_count' => 1],
                ],
            );

            OrderItem::query()->updateOrCreate(
                ['order_id' => $order2->id, 'product_id' => $organizer->id],
                ['quantity' => 1, 'unit_price_cents' => $organizer->price_cents, 'product_name' => $organizer->name]
            );
        }

        $this->upsertOrder(
            seedKey: 'sample-pending',
            attributes: [
                'product_id' => $mug->id,
                'amount_cents' => $mug->price_cents,
                'currency' => $mug->currency,
                'status' => 'pending',
                'customer_email' => 'checkout.pending@example.com',
                'customer_name' => 'Pending Checkout',
                'metadata' => ['source' => 'cart', 'item_count' => 1],
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function upsertOrder(string $seedKey, array $attributes): Order
    {
        $existing = Order::query()
            ->where('metadata->seed_key', $seedKey)
            ->first();

        $metadata = array_merge($attributes['metadata'] ?? [], ['seed_key' => $seedKey]);
        $attributes['metadata'] = $metadata;
        $attributes['reference'] = $existing?->reference ?? ('NL-'.Str::upper(Str::random(8)));

        if ($existing) {
            $existing->update($attributes);

            return $existing->fresh();
        }

        return Order::query()->create($attributes);
    }
}
