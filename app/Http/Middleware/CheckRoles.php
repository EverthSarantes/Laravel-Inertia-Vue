<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Users\UserModule;

class CheckRoles
{
    /**
     * Handle an incoming request and check if the user has the required role for a module.
     *
     * @param Request $request The incoming HTTP request.
     * @param Closure $next The next middleware in the pipeline.
     * @param string $module The module to check permissions for.
     * @return Response The HTTP response or a redirect if the user lacks permissions.
     */
    public function handle(Request $request, Closure $next, $module): Response
    {
        if(Auth::user()->role == '0') return $next($request);

        if(UserModule::where('user_id', Auth::user()->id)->whereHas('module', function($query) use ($module){$query->where('internal_name', $module);})->exists()) return $next($request);

        return redirect()->route('panel')->with([
            'message' => [
                'message' => 'No tienes permisos para acceder a este módulo',
                'type' => 'danger',
            ],
        ], 403);
    }
}
