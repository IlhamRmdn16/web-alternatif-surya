<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Menjaga satu alamat resmi (APP_URL), mis. https://hondagarut.id, agar www, http, atau domain lain
 * tidak dianggap Google sebagai situs kembar. Hanya aktif di production.
 */
class CanonicalHost
{
    public function handle(Request $request, Closure $next)
    {
        if (! app()->environment('production') || ! config('seo.canonical_redirect') || ! $request->isMethod('GET')) {
            return $next($request);
        }

        $canonical = parse_url((string) config('app.url'));
        $host = strtolower($canonical['host'] ?? '');
        if ($host === '' || in_array($host, ['localhost', '127.0.0.1'], true)) {
            return $next($request);
        }

        $scheme = $canonical['scheme'] ?? 'https';
        $wrongHost = strtolower($request->getHost()) !== $host;
        $wrongScheme = $scheme === 'https' && ! $request->isSecure();

        if ($wrongHost || $wrongScheme) {
            return redirect()->to($scheme.'://'.$host.$request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
