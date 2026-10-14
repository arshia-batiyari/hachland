<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RulesController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        $scopes = $request->user()->pendingRuleScopes();

        if ($scopes === []) {
            return redirect($this->destination($request));
        }

        return view('rules.show', ['scopes' => $scopes]);
    }

    public function accept(Request $request): RedirectResponse
    {
        $request->validate([
            'accepted' => ['accepted'],
        ]);

        $scopes = $request->user()->pendingRuleScopes();
        abort_if($scopes === [], 404);

        $request->user()->acceptRules($scopes);

        return redirect($this->destination($request));
    }

    private function destination(Request $request): string
    {
        $user = $request->user();

        if ($user->isVendor()) {
            return $user->booth?->status === 'approved'
                ? route('vendor.dashboard')
                : route('vendor.pending');
        }

        if ($user->isLandowner()) {
            return route('landowner.dashboard');
        }

        return route('home');
    }
}
