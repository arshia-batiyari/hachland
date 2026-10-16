<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * استفاده در routes: ->middleware('role:admin')  یا  ->middleware('role:vendor,admin')
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles, true)) {
            abort(403, 'شما دسترسی لازم برای مشاهده این صفحه را ندارید.');
        }

        // اگر نقش «غرفه‌دار» است ولی هنوز غرفه‌اش تأیید نشده، به صفحه انتظار هدایتش کن
        if ($request->user()->role === 'vendor'
            && $request->user()->booth?->status !== 'approved'
            && ! $request->routeIs('vendor.pending', 'vendor.booth.setup', 'logout')) {
            return redirect()->route('vendor.pending');
        }

        // بعد از تأیید، اولین ورود به پنل صفحهٔ قوانین همان حوزه است.
        if ($request->user()->pendingRuleScopes() !== []
            && ! $request->routeIs('rules.show', 'rules.accept', 'vendor.pending', 'logout')) {
            return redirect()->route('rules.show');
        }

        return $next($request);
    }
}
