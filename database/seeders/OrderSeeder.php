<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Commerce\Models\OrderItem;
use App\Domain\Payments\Models\Order;
use App\Domain\Payments\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $throw = Product::query()->where('slug', 'merino-wool-throw')->first();
        $mug = Product::query()->where('slug', 'stoneware-mug-set')->first();
        $organizer = Product::query()->where('slug', 'oak-desk-organizer')->first();
        $patient = User::query()->where('email', 'patient@example.com')->first();

        if (! $throw || ! $mug) {
            return;
        }

        $order1 = Order::query()->updateOrCreate(
            ['reference' => 'NL-DEMO001'],
            [
                'user_id' => $patient?->id,
                'product_id' => $throw->id,
                'amount_cents' => $throw->price_cents + $mug->price_cents,
                'currency' => 'USD',
                'status' => 'paid',
                'customer_email' => 'alex.rivera@example.com',
                'customer_name' => 'Alex Rivera',
                'metadata' => ['source' => 'cart', 'item_count' => 2],
            ]
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
            Order::query()->updateOrCreate(
                ['reference' => 'NL-DEMO002'],
                [
                    'product_id' => $organizer->id,
                    'amount_cents' => $organizer->price_cents,
                    'currency' => 'USD',
                    'status' => 'paid',
                    'customer_email' => 'sam@example.com',
                    'customer_name' => 'Sam Chen',
                    'metadata' => ['source' => 'cart', 'item_count' => 1],
                ]
            );

            $order2 = Order::query()->where('reference', 'NL-DEMO002')->first();
            if ($order2) {
                OrderItem::query()->updateOrCreate(
                    ['order_id' => $order2->id, 'product_id' => $organizer->id],
                    ['quantity' => 1, 'unit_price_cents' => $organizer->price_cents, 'product_name' => $organizer->name]
                );
            }
        }

        Order::query()->updateOrCreate(
            ['reference' => 'NL-DEMO003'],
            [
                'product_id' => $mug->id,
                'amount_cents' => $mug->price_cents,
                'currency' => 'USD',
                'status' => 'pending',
                'customer_email' => 'pending@example.com',
                'customer_name' => 'Pending Checkout',
                'metadata' => ['source' => 'cart'],
            ]
        );
    }
}
