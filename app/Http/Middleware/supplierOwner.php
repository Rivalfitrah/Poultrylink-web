<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class supplierOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $currentUser = Auth::user();
        $supplier = $supplier::findOrFail($request->id);
        
        if($supplier->user_id != $currentUser->id){
            return response()->json(['message' => 'data not found']);
        }

        return $next($request);
    }
}
