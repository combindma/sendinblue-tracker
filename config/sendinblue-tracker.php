<?php

return [
    'tracker_id' => env('SENDINBLUE_TRACKER_ID', null),

    /*
     * The key under which data is saved to the session with flash.
     */
    'sessionKey' => env('SENDINBLUE_TRACKER_SESSION_KEY', strtolower(config('app.name')).'_sendinbluetracker'),

    /*
     * Enable or disable script rendering. Useful for local development.
     */
    'enabled' => env('ENABLE_SENDINBLUE_TRACKER', false),
];
