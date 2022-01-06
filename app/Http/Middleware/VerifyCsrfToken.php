<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Temporary
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, \Closure $next)
    {
        /*if (in_array(env('APP_ENV'), ['local', 'dev'])) {
            return $next($request);
        }*/

        return parent::handle($request, $next);
    }

    /**
     * The URIs that should be excluded from CSRF verification.
     * Stripe webhooks
     *
     * @var array
     */
    protected $except = [
        'stripe/*',
        'calendar'
    ];
}
