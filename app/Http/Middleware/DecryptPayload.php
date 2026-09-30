<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DecryptPayload
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $privateKey = env('RSA_PRIVATE_KEY');

        if (!$privateKey) {
            return response()->json(['message' => 'RSA Private key is not configured.'], 500);
        }

        $fields = ['username', 'email', 'password', 'nik'];

        foreach ($fields as $field) {
            if (!$request->filled($field))
                continue;

            $value = $request->input($field);

            if (!is_string($value))
                continue;

            $decoded = base64_decode($value, true);

            if ($decoded === false) {
                return response()->json(['message' => "Invalid encrypted {$field} payload."], 422);
            }

            $plain = null;
            $ok = openssl_private_decrypt($decoded, $plain, $privateKey, OPENSSL_PKCS1_PADDING);

            if (!$ok) {
                return response()->json(['message' => "Unable to decrypt {$field} payload."], 422);
            }
            $request->merge([$field => $plain]);
        }
        return $next($request);
    }
}
