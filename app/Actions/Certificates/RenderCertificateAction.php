<?php

namespace App\Actions\Certificates;

use App\Jobs\CreatePDF;
use App\Models\Certificate;
use App\Models\Template;

final readonly class RenderCertificateAction
{
    public function execute(Template $template, Certificate $certificate): void
    {
        CreatePDF::dispatch($template, $certificate);
    }
}
