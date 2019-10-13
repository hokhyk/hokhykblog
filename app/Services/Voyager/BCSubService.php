<?php

namespace App\Services\Voyager;

use App\Services\Voyager\ISubService;

class BCSubService implements ISubService
{
    public function sub(float $num1, float $num2, int $scale = 10):string {
        // Todo: refactor scale parameter to be configurable in config files or .env...
        return bcsub($num1, $num2, $scale);
    }
}
