<?php

namespace App\Services;

use Sqids\Sqids;

class TokenService
{
    private Sqids $sqids;

    public function __construct()
    {
        $this->sqids = new Sqids(minLength: 6);
    }

    public function createToken(int $number): string
    {
        return $this->sqids->encode([$number]);
    }
}
