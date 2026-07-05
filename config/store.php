<?php

declare(strict_types=1);

return [

    'name' => 'Northline',
    'legal_name' => 'Northline Mercantile',
    'tagline' => 'Goods for everyday ritual',
    'description' => 'Curated home, desk, and travel essentials — designed to last, shipped with care.',

    'contact_email' => 'hello@northline.store',

    'currency' => 'USD',
    'free_shipping_threshold_cents' => 7500,

    'categories' => [
        'home' => 'Home & Living',
        'desk' => 'Desk & Office',
        'outdoor' => 'Travel & Outdoor',
    ],

    'filters' => [
        'sort' => [
            'featured' => 'Featured',
            'newest' => 'Newest',
            'price_asc' => 'Price: low to high',
            'price_desc' => 'Price: high to low',
            'name' => 'Name A–Z',
        ],
        'price_ranges' => [
            '' => 'Any price',
            'under_50' => 'Under $50',
            '50_100' => '$50 – $100',
            'over_100' => 'Over $100',
        ],
    ],

    'trust' => [
        'Free shipping over $75',
        'Secure Stripe checkout',
        '30-day easy returns',
    ],

];
