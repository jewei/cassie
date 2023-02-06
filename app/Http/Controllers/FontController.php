<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class FontController extends Controller
{
    public function index(): InertiaResponse
    {
        return Inertia::render('Font/Index', [
            'fonts' => Storage::disk('local')->files('fonts'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Storage::putFileAs(
            'fonts',
            $request->file('font'),
            $request->file('font')->getClientOriginalName()
        );

        return redirect()
            ->intended(route('fonts.index'))
            ->with('status', 'font uploaded.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        if (Storage::disk('local')->exists($request->input('filename'))) {
            Storage::disk('local')->delete($request->input('filename'));
        }

        return redirect()
            ->intended(route('fonts.index'))
            ->with('status', 'font deleted.');
    }
}
