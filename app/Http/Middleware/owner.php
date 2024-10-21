<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class owner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUser = Auth::user();
        $buyer = Buyer::findOrFail($request->id);

        if($buyer->user_id != $currentUser->id){
            return response()->json(['message' => 'data not found']);
        }

        // return response()->json($buyer);
        return $next($request);
    }
}
