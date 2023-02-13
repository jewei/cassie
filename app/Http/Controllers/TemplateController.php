<?php

namespace App\Http\Controllers;

use App\Actions\Fonts\FetchFontsAction;
use App\Actions\Pdf\RegenerateTemplatePdfsAction;
use App\Actions\Templates\DeleteTemplateAction;
use App\Actions\Templates\FetchTemplatesAction;
use App\Actions\Templates\UpdateTemplateAction;
use App\Models\Template;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class TemplateController extends Controller
{
    public function index(FetchTemplatesAction $action): InertiaResponse
    {
        return Inertia::render('Template/Index', ['templates' => $action->execute()]);
    }

    public function create(FetchFontsAction $action): InertiaResponse
    {
        return Inertia::render('Template/Form', [
            'template' => new Template(),
            'paperFonts' => $action->execute(),
            'maxUploadSize' => min(ini_get('post_max_size'), ini_get('upload_max_filesize')),
            'mode' => 'create',
        ]);
    }

    public function edit(Template $template, FetchFontsAction $action): InertiaResponse
    {
        return Inertia::render('Template/Form', [
            'template' => $template,
            'paperFonts' => $action->execute(),
            'maxUploadSize' => min(ini_get('post_max_size'), ini_get('upload_max_filesize')),
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Template $template, UpdateTemplateAction $action): RedirectResponse
    {
        $action->execute($template, $request->all());

        return redirect()->intended(route('templates.index'))->with('status', 'template updated.');
    }

    public function store(Request $request, UpdateTemplateAction $action): RedirectResponse
    {
        $action->execute(new Template(), $request->all());

        return redirect()->intended(route('templates.index'))->with('status', 'template saved.');
    }

    public function destroy(Template $template, DeleteTemplateAction $action): RedirectResponse
    {
        $action->execute($template);

        return redirect()->intended(route('templates.index'))->with('status', 'template deleted.');
    }

    public function regenerate(Template $template, RegenerateTemplatePdfsAction $action): RedirectResponse
    {
        $action->execute($template);

        return redirect()->intended(route('templates.certificates.index', $template))->with('status', 'template deleted.');
    }
}
