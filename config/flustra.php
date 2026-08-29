<?php

return [
    'api_url' => env('FLUSTRA_API_URL', 'https://wa.flustra.id/api/v1/messages/text'),
    'api_key' => env('FLUSTRA_API_KEY', ''),
    'webhook_secret' => env('FLUSTRA_WEBHOOK_SECRET', ''),
    'bank_info' => env('RZ_BANK_INFO', 'BCA 1234567890 a.n RZ Digital Creative'),
    
    // Default package pricing in IDR
    'packages' => [
        'landing_page' => [
            'name' => 'Landing Page',
            'price' => 499000,
            'description' => 'Website 1 halaman konversi tinggi, cepat, mobile-friendly.',
        ],
        'company_profile' => [
            'name' => 'Company Profile',
            'price' => 999000,
            'description' => 'Website profesional profil perusahaan lengkap dengan halaman tentang, layanan, & kontak.',
        ],
        'toko_kasir' => [
            'name' => 'Toko & Kasir POS',
            'price' => 1500000,
            'description' => 'Website e-commerce / katalog produk terintegrasi sistem kasir POS UMKM.',
        ],
        'custom' => [
            'name' => 'Custom Web App',
            'price' => 2500000,
            'description' => 'Aplikasi web atau sistem informasi kustom sesuai kebutuhan bisnis.',
        ],
    ],
    
    // Default maintenance pricing in IDR
    'default_maintenance_price' => 150000,
];
