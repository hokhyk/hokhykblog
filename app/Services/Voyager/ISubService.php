<?php

namespace App\Services\Voyager;

interface ISubService
{
    public function sub(float $num1, float $num2, int $scale):string;
}
