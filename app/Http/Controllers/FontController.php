<?php

namespace App\Http\Controllers;

use App\Actions\Fonts\DeleteFontsAction;
use App\Actions\Fonts\SaveFontAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FontController extends Controller
{
    public function store(Request $request, SaveFontAction $action): RedirectResponse
    {
        Validator::make($request->all(), [
            'font' => ['required', 'mimetypes:font/sfnt', 'max:4096'],
        ])->validate();

        $action->execute($request->file('font'));

        return redirect()
            ->intended(route('settings.index'))
            ->with('status', 'font uploaded.');
    }

    public function destroy(Request $request, DeleteFontsAction $action): RedirectResponse
    {
        Validator::make($request->all(), [
            'filename' => ['required', 'string', 'max:255'],
        ])->validate();

        $action->execute((string) $request->input('filename'));

        return redirect()
            ->intended(route('settings.index'))
            ->with('status', 'font deleted.');
    }
}
