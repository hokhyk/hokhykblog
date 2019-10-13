<?php

namespace App\Services\Voyager;

interface IAddService
{
    public function add(float $num1, float $num2, int $scale):string;
}
