<?php

namespace App\Services;

class ValueConversionService
{
    public function toPayPal(int $value): float
    {
        return round($value / 100, 2);
    }

    public function fromPayPal(float $value): int
    {
        return (int) ($value * 100);
    }

}
