<?php

namespace App\Http\Controllers;

use App\Actions\Templates\DeleteTemplateAction;
use App\Actions\Templates\FetchTemplatesAction;
use App\Actions\Templates\UpdateTemplateAction;
use App\Models\Template;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TemplateController extends Controller
{
    public function index(FetchTemplatesAction $action)
    {
        return Inertia::render('Template/Index', ['templates' => $action->execute()]);
    }

    public function create()
    {
        return Inertia::render('Template/Form', [
            'template' => new Template(),
            'maxUploadSize' => min(ini_get('post_max_size'), ini_get('upload_max_filesize')),
            'mode' => 'create',
        ]);
    }

    public function edit(Template $template)
    {
        return Inertia::render('Template/Form', [
            'template' => $template,
            'maxUploadSize' => min(ini_get('post_max_size'), ini_get('upload_max_filesize')),
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Template $template, UpdateTemplateAction $action)
    {
        $action->execute($template, $request->all());

        return redirect()->intended(route('templates.index'))->with('status', 'template updated.');
    }

    public function store(Request $request, UpdateTemplateAction $action)
    {
        $action->execute(new Template(), $request->all());

        return redirect()->intended(route('templates.index'))->with('status', 'template saved.');
    }

    public function destroy(Template $template, DeleteTemplateAction $action)
    {
        $action->execute($template);

        return redirect()->intended(route('templates.index'))->with('status', 'template deleted.');
    }
}
