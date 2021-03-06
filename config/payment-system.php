<?php

$seconds = 1;
$minutes = 60 * $seconds;
$hours = 60 * $minutes;
$days = 24 * $hours;
$weeks = 7 * $days;

return [
    'bit-skins-key' => env('BIT_SKINS_API'),

    'minimum-steam-item-value' => 30,

    'webhooks' => [
        'periods' => [
            5 * $minutes,
            15 * $minutes,
            30 * $minutes,
            1 * $hours,
            2 * $hours,
            4 * $hours,
            6 * $hours,
            12 * $hours,
            1 * $days,
            2 * $days,
            4 * $days,
            7 * $days,
        ],
    ],

    'rechecking-periods' => [
        1 * $minutes,
        2 * $minutes,
        5 * $minutes,
        10 * $minutes,
        20 * $minutes,
        30 * $minutes,
        1 * $hours,
        2 * $hours,
        4 * $hours,
        6 * $hours,
        8 * $hours,
        12 * $hours,
        1 * $days,
        2 * $days,
        3 * $days,
        4 * $days,
        1 * $weeks,
        2 * $weeks,
    ],
];
