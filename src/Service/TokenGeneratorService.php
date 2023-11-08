<?php
namespace App\Service;


class TokenGeneratorService
{
    public function generateToken(int $length = 32): String{
        $randomBytes = random_bytes($length);

        $token = bin2hex($randomBytes);

        return $token;
    }

}