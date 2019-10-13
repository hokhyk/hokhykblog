<?php

namespace App\Services\Voyager;

interface IDivService
{
    public function div(float $num1, float $num2, int $scale):string;
}
