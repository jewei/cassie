<?php

namespace App\Actions\Pdf;

use App\Models\Template;

final readonly class RegenerateTemplatePdfsAction
{
    public function __construct(
        private DeletePdfAction $deletePdfAction,
        private RenderPdfAction $renderPdfAction,
    ) {
    }

    public function execute(Template $template): void
    {
        $template->certificates->each(function ($certificate) use ($template) {
            $certificate->update([
                'rendered_at' => null,
            ]);
            $this->deletePdfAction->execute($certificate);
            $this->renderPdfAction->execute($template, $certificate);
        });
    }
}
