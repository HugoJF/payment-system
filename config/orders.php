<?php

$seconds = 1000;
$minutes = $seconds * 60;
$hours = $minutes * 60;
$days = $hours * 24;

return [
	'periods' => [
		1 * $seconds,
		30 * $seconds,
		1 * $minutes,
		5 * $minutes,
		10 * $minutes,
		30 * $minutes,
		1 * $hours,
		2 * $hours,
		4 * $hours,
		12 * $hours,
		1 * $days,
	],
];