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
    | Allowed Media File Types
    |--------------------------------------------------------------------------
    |
    | Define the allowed file types for uploads.
    | - accept: The HTML accept attribute value for the file input.
    | - allowed_types: JS array of allowed mime types for client-side check.
    | - allowed_extensions: Comma-separated list of allowed extensions for Laravel validation.
    |
    */
    'accept' => 'image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/vcard',
    'allowed_types' => ['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/vcard'],
    'allowed_extensions' => 'jpeg,png,jpg,gif,webp,svg,pdf,doc,docx,xls,xlsx,vcf',
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
