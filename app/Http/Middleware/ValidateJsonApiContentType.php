<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateJsonApiContentType
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */

    public function handle(Request $request, Closure $next): Response
    {
        // No se han cumplido las validaciones, devuelve error
        if (strcasecmp($request->header('accept'), 'application/vnd.api+json') !== 0) {
            return response()->json([
                "errors" => [
                    "status" => 406,
                    "title" => "Not Acceptable",
                    "details" => "Content File not specified"
                ]
            ], 406);
        }

        // Devuelve la respuesta con normalidad
        return $next($request);
    }
}
