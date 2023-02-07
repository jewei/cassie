<?php

namespace App\Http\Controllers;

use App\Actions\Fonts\FetchFontsAction;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class SettingController extends Controller
{
    public function index(
        FetchFontsAction $fetchFontsAction
    ): InertiaResponse {
        return Inertia::render('Font/Index', [
            'fonts' => $fetchFontsAction->execute(),
        ]);
    }
}
