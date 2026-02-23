<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Question visible after competition start (seconds)
    |--------------------------------------------------------------------------
    | Number of seconds after competition start_at before questions are shown
    | to visitors. Until then, a countdown is displayed.
    */
    'question_visible_after_seconds' => (int) env('QUIZ_QUESTION_VISIBLE_AFTER_SECONDS', 30),

];
