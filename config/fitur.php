<?php

return [
    // Overall POS system status
    "status" => true,

    // Feature toggles for POS page
    "features" => [
        "search" => true,           // Product search functionality
        "category_filter" => true,  // Category filter dropdown
        "cart" => true,             // Add to cart functionality
        "cart_button" => true,      // Cart button in header
        "history_button" => true,   // View History button
        "stock_display" => true,    // Show stock information
        "category_badge" => true,   // Show category badge on products
    ],

    // Admin features
    "admin" => [
        "categories" => true,       // Category management
        "products" => true,         // Product management
        "transactions" => true,     // Transaction management
        "reports" => true,          // Reports functionality
    ],

    // UI responsiveness settings
    "ui" => [
        "responsive_grid" => true,  // Responsive product grid
        "mobile_optimized" => true, // Mobile-friendly layout
    ],
];
