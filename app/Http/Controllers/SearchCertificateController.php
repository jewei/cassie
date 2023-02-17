<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class SearchCertificateController extends Controller
{
    public function index(): InertiaResponse
    {
        return Inertia::render('Search/Index');
    }

    public function search(Request $request): InertiaResponse
    {
        $request->validate([
            'uuid' => ['required', 'string', 'exists:certificates,uuid'],
        ]);

        $certificate = Certificate::where('uuid', $request->input('uuid'))->first();

        return Inertia::render('Search/Index', [
            'url' => route('public.show-certificate', $certificate),
        ]);
    }
}
