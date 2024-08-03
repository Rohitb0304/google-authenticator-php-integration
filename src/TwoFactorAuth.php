<?php
namespace App;

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

class TwoFactorAuth
{
    private $gAuth;

    public function __construct()
    {
        $this->gAuth = new GoogleAuthenticator();
    }

    public function generateSecret(): string
    {
        return $this->gAuth->generateSecret();
    }

    public function getQRCodeUrl(string $user, string $secret, string $title): string
    {
        return GoogleQrUrl::generate($user, $secret, $title);
    }

    public function verifyCode(string $secret, string $code): bool
    {
        return $this->gAuth->checkCode($secret, $code);
    }
}