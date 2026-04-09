<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Published SPA build version
    |--------------------------------------------------------------------------
    |
    | Set this to match the frontend VITE_APP_VERSION when you deploy. When a
    | signed-in client sends a different ?client_version= on /api/notifications,
    | the API records a one-time "new version" notification for that user.
    |
    */
    'client_version' => env('CASH_TRACK_CLIENT_VERSION', '1.0.0'),

];
