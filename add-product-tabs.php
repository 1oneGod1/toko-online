<?php

/**
 * This script adds reviews and discussions tabs to the product detail page
 * Run with: php add-product-tabs.php
 */

$productShowPath = __DIR__ . '/resources/views/products/show.blade.php';
$productShow = file_get_contents($productShowPath);

if (!$productShow) {
    echo "❌ Could not read the product show view file.\n";
    exit(1);
}

// Create a backup of the original file
$backupPath = __DIR__ . '/resources/views/products/show.blade.php.bak';
file_put_contents($backupPath, $productShow);
echo "✅ Created backup of product show view at $backupPath\n";

// Find the position where the product description card ends
$endOfDescription = stripos($productShow, '</div>
                </div>');

if ($endOfDescription === false) {
    echo "❌ Could not find the end of the product description card.\n";
    exit(1);
}

// Move to the end of this div
$endOfDescription = strpos($productShow, '</div>', $endOfDescription + 1);

// Add tabs after the product description
$tabsCode = <<<'HTML'

                <!-- Tabs for Reviews and Discussions -->
                <ul class="nav nav-tabs mb-4" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-tab-pane" type="button" role="tab" aria-controls="reviews-tab-pane" aria-selected="true">
                            Ulasan <span class="badge rounded-pill bg-secondary" id="review-count">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="discussions-tab" data-bs-toggle="tab" data-bs-target="#discussions-tab-pane" type="button" role="tab" aria-controls="discussions-tab-pane" aria-selected="false">
                            Diskusi <span class="badge rounded-pill bg-secondary" id="discussion-count">0</span>
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content" id="productTabsContent">
                    <div class="tab-pane fade show active" id="reviews-tab-pane" role="tabpanel" aria-labelledby="reviews-tab" tabindex="0">
                        <x-product.reviews :product="$product" />
                    </div>
                    <div class="tab-pane fade" id="discussions-tab-pane" role="tabpanel" aria-labelledby="discussions-tab" tabindex="0">
                        <x-product.discussions :product="$product" />
                    </div>
                </div>
HTML;

// Insert the tabs after the product description
$newContent = substr($productShow, 0, $endOfDescription + 6) . $tabsCode . substr($productShow, $endOfDescription + 6);

// Update the file
file_put_contents($productShowPath, $newContent);
echo "✅ Updated product show view with reviews and discussions tabs.\n";

// Check if we need to modify the product card view
$productCardPath = __DIR__ . '/resources/views/components/product/card.blade.php';
if (file_exists($productCardPath)) {
    $productCard = file_get_contents($productCardPath);
    
    if (strpos($productCard, 'star-rating') === false) {
        // Find where to add star ratings in the card
        $pricePosition = strpos($productCard, '{{ number_format($product->price, 0, \',\', \'.\') }}');
        if ($pricePosition !== false) {
            // Find the end of the price line
            $endOfPriceLine = strpos($productCard, '</p>', $pricePosition);
            if ($endOfPriceLine !== false) {
                // Add star rating right after the price
                $ratingCode = "\n                <div class=\"mt-2\">\n                    <x-product.star-rating :rating=\"\$product->average_rating\" />\n                    <small class=\"text-muted ms-1\">{{ \$product->rating_count }}</small>\n                </div>";
                $newCardContent = substr($productCard, 0, $endOfPriceLine + 4) . $ratingCode . substr($productCard, $endOfPriceLine + 4);
                
                // Backup the card file
                file_put_contents($productCardPath . '.bak', $productCard);
                echo "✅ Created backup of product card view.\n";
                
                // Update the card file
                file_put_contents($productCardPath, $newCardContent);
                echo "✅ Updated product card view with star ratings.\n";
            }
        }
    } else {
        echo "ℹ️ Product card already has star ratings.\n";
    }
}

// Add JavaScript for the reviews and discussions counters to the layout
$layoutPath = __DIR__ . '/resources/views/products/show.blade.php';
$layout = file_get_contents($layoutPath);

// Check if we already have the script
if (strpos($layout, 'updateCounters') === false) {
    // Find the end of the page
    $endOfPage = strrpos($layout, '</x-layouts.app>');
    if ($endOfPage !== false) {
        // Add script just before the end tag
        $scriptCode = <<<'HTML'

@push('scripts')
<script>
    // Update counters for reviews and discussions tabs
    document.addEventListener('DOMContentLoaded', function() {
        const reviewCount = {{ $product->rating_count }};
        const discussionCount = {{ $product->discussions()->count() }};
        
        document.getElementById('review-count').textContent = reviewCount;
        document.getElementById('discussion-count').textContent = discussionCount;
    });
</script>
@endpush
HTML;

        $newLayout = substr($layout, 0, $endOfPage) . $scriptCode . substr($layout, $endOfPage);
        file_put_contents($layoutPath, $newLayout);
        echo "✅ Added counter update script to product detail page.\n";
    }
}

echo "\nDone! 🎉 Reviews and discussions features have been added to your store.\n";
echo "Make sure to run the migrations to create the necessary database tables:\n";
echo "php artisan migrate\n";
echo "php update-web-routes.php\n";