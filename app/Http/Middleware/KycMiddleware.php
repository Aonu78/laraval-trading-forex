<?php

namespace App\Http\Middleware;

use App\Models\Configuration;
use Closure;
use Illuminate\Http\Request;

class KycMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $general = Configuration::first();

        if (! $general || ! $general->is_allow_kyc) {
            return $next($request);
        }

        $user = auth()->user();

        if (! $user || (int) $user->is_kyc_verified === 1) {
            return $next($request);
        }

        if ($this->canBrowseWithoutKyc($request)) {
            return $next($request);
        }

        $message = (int) $user->is_kyc_verified === 2
            ? 'Your KYC verification is pending. Please wait for admin approval before performing actions.'
            : 'Please complete your KYC verification first.';

        return redirect()->route('user.kyc')->with('error', $message);
    }

    private function canBrowseWithoutKyc(Request $request): bool
    {
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return ! in_array($request->route()?->getName(), [
                'user.tradeClose',
                'user.ticket.status-change',
            ], true);
        }

        return false;
    }
}
