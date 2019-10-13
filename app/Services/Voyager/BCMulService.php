<?php

namespace App\Services\Voyager;

use App\Services\Voyager\IMulService;

class BCMulService implements IMulService
{
    public function mul(float $num1, float $num2, int $scale = 10):string {
        // Todo: refactor scale parameter to be configurable in config files or .env...
        return bcmul($num1, $num2, $scale);
    }
}
