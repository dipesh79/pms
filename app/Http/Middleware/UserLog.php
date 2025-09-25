<?php

namespace App\Http\Middleware;

use App\Models\Log;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserLog
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = optional($request->user())->id;
        $endpoint = $request->path();
        Log::create([
            'user_id' => $userId,
            'endpoint' => $endpoint,
        ]);
        return $next($request);
    }
}
