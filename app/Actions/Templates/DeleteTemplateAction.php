<?php

namespace App\Actions\Templates;

use App\Actions\Certificates\DeleteCertificateAction;
use App\Models\Template;

final readonly class DeleteTemplateAction
{
    public function __construct(
        private DeleteBackgroundAction $deleteBackgroundAction,
        private DeleteCertificateAction $deleteCertificateAction,
    ) {
    }

    public function execute(Template $template): void
    {
        $this->deleteBackgroundAction->execute($template);

        $template->certificates->each(function ($certificate) {
            $this->deleteCertificateAction->execute($certificate);
        });

        $template->delete();
    }
}
