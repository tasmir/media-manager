<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Media Manager Asset Paths
    |--------------------------------------------------------------------------
    |
    | These values define where the media manager's CSS and JS files are located
    | in the public directory.
    |
    */
    'assets' => [
        'css' => 'vendor/media-manager/css/media-manager.css',
        'js'  => 'vendor/media-manager/js/media-manager.min.js',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Upload Path
    |--------------------------------------------------------------------------
    |
    | The default directory where media files will be uploaded.
    |
    */
    'upload_path' => 'media',
    'active_tailwindcss' => true,

    /*
     * Paginate Items
     */
    'paginate' => 36,
    /*
    |--------------------------------------------------------------------------
    | Image Optimization
    |--------------------------------------------------------------------------
    |
    | Options for image optimization and conversion.
    |
    */
    'enable_webp_convert' => true,
    'image_quality' => 80,

    /*
    |--------------------------------------------------------------------------
    | Media Caching
    |--------------------------------------------------------------------------
    |
    | Browser caching settings for served media files.
    |
    */
    'media_cache' => [
        'enable' => true,
        'expiry' => 86400 * 30, // 30 days
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload Restrictions
    |--------------------------------------------------------------------------
    |
    | Maximum file size allowed for uploads in Megabytes (MB).
    |
    */
    'max_file_size' => 5, // 5MB

    /*
    |--------------------------------------------------------------------------
    | Component Design
    |--------------------------------------------------------------------------
    |
    | Default styling and text for the media picker component.
    |
    */
    'button_class' => 'w-20 h-20 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-xl hover:border-indigo-400 hover:bg-indigo-50/50 transition-all text-slate-400 hover:text-indigo-600 group',
    'button_text' => 'Choose',
];
