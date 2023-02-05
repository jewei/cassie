<?php

namespace App\Actions\Certificates;

use App\Models\Certificate;

final readonly class ShowCertificateAction
{
    public function __construct(
        private RenderCertificateAction $renderCertificateAction,
    ) {
    }

    public function execute(Certificate $certificate): void
    {
        if (! $certificate->file_exists) {
            $this->renderCertificateAction->execute($certificate->template, $certificate);
        }
    }
}
