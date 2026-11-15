<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // მაგალითი: თუ იუზერი არ არის ავტორიზებული ან არ არის ადმინი
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', წვდომა შეზღუდულია!);
        }

        // თუ ყველაფერი რიგზეა, უშვებს შემდეგ ეტაპზე (როუტამდე / კონტროლერამდე)
        return $next($request);
    }
}