<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnableUserModelFilters
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        foreach (get_declared_classes() as $class) {
            if (is_subclass_of($class, \Illuminate\Database\Eloquent\Model::class) &&
                in_array(\App\Traits\ModelFilters\HasUserModelFilters::class, class_uses_recursive($class))) {
                $class::enableUserModelFilters();
            }
        }

        return $next($request);
    }
}
