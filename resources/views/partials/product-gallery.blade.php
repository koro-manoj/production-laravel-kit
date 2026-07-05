@php
    $images = $product->images();
    $galleryId = 'gallery-'.$product->id;
@endphp

<div class="product-gallery" id="{{ $galleryId }}">
    <div class="product-gallery-main">
        @if($product->badge)
            <span class="product-badge">{{ $product->badge }}</span>
        @endif

        @if($images !== [])
            <img
                src="{{ $images[0] }}"
                alt="{{ $product->name }}"
                class="product-gallery-hero"
                id="{{ $galleryId }}-main"
                loading="eager"
            >
        @else
            <div @class([
                'product-thumb product-thumb-lg h-full w-full',
                match ($product->category) {
                    'desk' => 'product-thumb--desk',
                    'outdoor' => 'product-thumb--outdoor',
                    default => 'product-thumb--home',
                },
            ])>
                <span class="product-thumb-letter text-6xl">{{ mb_substr($product->name, 0, 1) }}</span>
            </div>
        @endif
    </div>

    @if(count($images) > 1)
        <div class="product-gallery-thumbs" role="tablist" aria-label="Product images">
            @foreach($images as $index => $url)
                <button
                    type="button"
                    role="tab"
                    aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                    aria-label="View image {{ $index + 1 }}"
                    @class(['product-gallery-thumb', 'product-gallery-thumb--active' => $index === 0])
                    data-gallery-target="{{ $galleryId }}-main"
                    data-gallery-url="{{ $url }}"
                    onclick="window.northlineGallerySelect(this)"
                >
                    <img src="{{ $url }}" alt="" loading="lazy">
                </button>
            @endforeach
        </div>
    @endif
</div>

@once
    @push('head')
        <script>
            window.northlineGallerySelect = function (button) {
                const targetId = button.dataset.galleryTarget;
                const url = button.dataset.galleryUrl;
                const img = document.getElementById(targetId);
                const gallery = button.closest('.product-gallery');

                if (img && url) {
                    img.src = url;
                }

                gallery?.querySelectorAll('.product-gallery-thumb').forEach((thumb) => {
                    const active = thumb === button;
                    thumb.classList.toggle('product-gallery-thumb--active', active);
                    thumb.setAttribute('aria-selected', active ? 'true' : 'false');
                });
            };
        </script>
    @endpush
@endonce
