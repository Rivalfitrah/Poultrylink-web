<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ulasanOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUser = Auth::user();
        $ulasan = Ulasan::findOrFail($request->id);
        if($ulasan->user_id != $currentUser->id){
            return response()->json(['message' => 'data not found']);
        }

        return $next($request);
    }
}
