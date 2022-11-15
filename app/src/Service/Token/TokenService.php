<?php

namespace App\Service\Token;

class TokenService
{
    /**
     * @throws \Exception
     */
    public function generateTokenString(): string
    {
        $randomBytes = random_bytes(random_int(32, 48));
        return bin2hex($randomBytes);
    }
}