<?php

namespace App\Actions\Certificates;

use App\Actions\Pdf\DeletePdfAction;
use App\Models\Certificate;

final readonly class DeleteCertificateAction
{
    public function __construct(
        private DeletePdfAction $deletePdfAction,
    ) {
    }

    public function execute(Certificate $certificate): void
    {
        $this->deletePdfAction->execute($certificate);

        $certificate->delete();
    }
}
