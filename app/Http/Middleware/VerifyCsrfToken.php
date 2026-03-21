<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Symfony\Component\HttpFoundation\Cookie;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * Create a new "LMS-XSRF-TOKEN" cookie to avoid collision
     * with parent domain XSRF-TOKEN cookies from other apps.
     */
    protected function newCookie($request, $config)
    {
        return new Cookie(
            'LMS-XSRF-TOKEN',
            $request->session()->token(),
            $this->availableAt(60 * $config['lifetime']),
            $config['path'],
            $config['domain'],
            $config['secure'],
            false,
            false,
            $config['same_site'] ?? null,
            $config['partitioned'] ?? false
        );
    }

    /**
     * Determine if the cookie contents should be serialized.
     */
    public static function serialized()
    {
        return EncryptCookies::serialized('LMS-XSRF-TOKEN');
    }
}
