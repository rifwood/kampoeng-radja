<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdatePinRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FirstPinChangeController extends Controller
{
    public function edit(Request $request): Response|RedirectResponse
    {
        if (! $request->user()?->must_change_pin) {
            return to_route('dashboard');
        }

        return Inertia::render('Auth/ChangePin');
    }

    public function update(UpdatePinRequest $request): RedirectResponse
    {
        $request->user()->update([
            'pin' => $request->validated('pin'),
            'must_change_pin' => false,
        ]);

        $request->session()->regenerate();

        return to_route('dashboard')->with('success', 'PIN berhasil diperbarui.');
    }
}
