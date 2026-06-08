<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Question visible after competition start (seconds)
    |--------------------------------------------------------------------------
    | Number of seconds after competition start_at before questions are shown
    | to visitors. Until then, a countdown is displayed.
    */
    'question_visible_after_seconds' => (int) env('QUIZ_QUESTION_VISIBLE_AFTER_SECONDS', 60),

    /*
    |--------------------------------------------------------------------------
    | Mobile API Secret Key for request signature verification
    |--------------------------------------------------------------------------
    */
    'api_secret_key' => env('QUIZ_API_SECRET_KEY', 'family_quiz_secret_2026'),
];
