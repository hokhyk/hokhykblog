<?php

namespace App\Services\Voyager;

use App\Services\Voyager\IDivService;

class BCDivService implements IDivService
{
    public function div(float $num1, float $num2, int $scale = 10):string {
        // Todo: refactor scale parameter to be configurable in config files or .env...
        return bcdiv($num1, $num2, $scale);
    }
}
