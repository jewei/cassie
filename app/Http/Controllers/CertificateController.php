<?php

namespace App\Http\Controllers;

use App\Actions\Certificates\DeleteCertificateAction;
use App\Actions\Certificates\ShowCertificateAction;
use App\Actions\Participants\FetchParticipantsAction;
use App\Actions\Participants\InvalidParticipantException;
use App\Actions\Participants\UpdateParticipantsAction;
use App\Models\Certificate;
use App\Models\Template;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CertificateController extends Controller
{
    public function create(Template $template, FetchParticipantsAction $action)
    {
        return Inertia::render('Participant/Form', [
            'template' => $template,
            'datatext' => trim($template->certificates->reduce(fn ($carry, $item) => $carry . PHP_EOL . $item->name . ', ' . $item->email)),
        ]);
    }

    public function show(Request $request, Template $template, Certificate $certificate, ShowCertificateAction $action)
    {
        $certificate->setRelation('template', $template);

        $action->execute($certificate);

        return $request->boolean('download')
            ? response()->download($certificate->path, $certificate->download_filename, [
                'Cache-Control' => 'no-cache, must-revalidate',
            ])
            : response()->file($certificate->path, [
                'Content-Disposition' => 'filename="' . $certificate->download_filename . '"',
            ]);
    }

    public function index(Template $template, FetchParticipantsAction $action)
    {
        return Inertia::render('Participant/Index', [
            'template' => $template,
            'participants' => $action->execute($template),
        ]);
    }

    public function store(Request $request, Template $template, UpdateParticipantsAction $action)
    {
        try {
            $action->execute($template, $request->all());
        } catch (InvalidParticipantException $e) {
            return back()->withErrors([
                'datatext' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->intended(route('templates.certificates.index', $template))
            ->with('status', 'participants saved.');
    }

    public function destroy(Certificate $certificate, DeleteCertificateAction $action)
    {
        $action->execute($certificate);

        return response()->noContent();
    }
}
