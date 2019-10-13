<?php

namespace App\Services\Voyager;

use App\Services\Voyager\IAddService;

class BCAddService implements IAddService
{
    public function add(float $num1, float $num2, int $scale = 10):string {
        // Todo: refactor scale parameter to be configurable in config files or .env...
        return bcadd($num1, $num2, $scale);
    }
}
