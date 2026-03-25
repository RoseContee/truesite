<?php

namespace App\Http\Middleware;

use App\Models\Complaint;
use App\Models\User;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\View;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (auth('admin')->check()) {
            View::share('like_number', User::like()->count());
            View::share('pending_complaints_number', Complaint::pending()->count());
        }
        $this->authenticate($request, $guards);

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ($request->is('admin') || $request->is('admin/*')) {
            return route('admin.login');
        }
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
