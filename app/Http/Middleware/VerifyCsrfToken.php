<?php

namespace App\Http\Middleware;
use Closure;
use Redirect;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'admin/orderbekraftelse',
        'kortbetalning',
        'storeCardPayment',
    ];

    public function handle( $request, Closure $next )
    {
        if (
            $this->isReading($request) ||
            $this->runningUnitTests() ||
            $this->shouldPassThrough($request) ||
            $this->tokensMatch($request)
        ) {
            return $this->addCookieToResponse($request, $next($request));
        }

        $notification = [
            'tokenMissMatch' => 'Tyvärr gick det inte att bekräfta din förfrågan. Var snäll och försök igen.',
            'alert-typ' => 'error'
        ];

        // redirect the user back to the last page and show error
        return Redirect::back()->with($notification);
    }
}
