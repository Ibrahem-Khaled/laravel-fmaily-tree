<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Large File Upload Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for handling large file uploads (150MB+)
    |
    */

    // Maximum file size in KB (150MB = 150000 KB)
    'max_file_size' => env('MAX_FILE_SIZE', 150000),

    // Maximum POST size in KB (200MB = 200000 KB)
    'max_post_size' => env('MAX_POST_SIZE', 200000),

    // Maximum execution time in seconds
    'max_execution_time' => env('MAX_EXECUTION_TIME', 300),

    // Maximum input time in seconds
    'max_input_time' => env('MAX_INPUT_TIME', 300),

    // Memory limit in MB
    'memory_limit' => env('MEMORY_LIMIT', 512),

    // Chunk size for large file uploads (in bytes)
    'chunk_size' => env('UPLOAD_CHUNK_SIZE', 1048576), // 1MB

    // Temporary directory for large uploads
    'temp_directory' => storage_path('app/temp/large-uploads'),

    // Allowed file types for large uploads
    'allowed_types' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/bmp',
        'image/tiff',
    ],

    // Progress tracking configuration
    'progress_tracking' => [
        'enabled' => env('UPLOAD_PROGRESS_ENABLED', true),
        'session_key' => 'upload_progress',
        'cleanup_after' => 3600, // 1 hour
    ],

    // Error handling
    'error_handling' => [
        'log_errors' => env('LOG_UPLOAD_ERRORS', true),
        'max_retries' => env('UPLOAD_MAX_RETRIES', 3),
        'retry_delay' => env('UPLOAD_RETRY_DELAY', 5), // seconds
    ],
];
