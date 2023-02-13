<?php

namespace App\Actions\Certificates;

use App\Actions\Pdf\RenderPdfAction;
use App\Models\Certificate;

final readonly class ShowCertificateAction
{
    public function __construct(
        private RenderPdfAction $renderPdfAction,
    ) {
    }

    public function execute(Certificate $certificate): void
    {
        if (! $certificate->file_exists) {
            $this->renderPdfAction->execute($certificate->template, $certificate);
        }
    }
}
