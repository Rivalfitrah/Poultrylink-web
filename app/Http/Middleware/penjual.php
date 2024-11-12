<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class penjual
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $isSupplier = Supplier::where('user_id', $user->id)->exists();
        if (!$isSupplier) {
            return response()->json(['error' => 'You are not a supplier.'], 403);
        }
        return $next($request);
    }
}
