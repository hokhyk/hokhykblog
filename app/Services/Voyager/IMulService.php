<?php

namespace App\Services\Voyager;

interface IMulService
{
    public function mul(float $num1, float $num2, int $scale):string;
}
