<?php

namespace App\Actions\Pdf;

use App\Jobs\CreatePDF;
use App\Models\Certificate;
use App\Models\Template;

final readonly class RenderPdfAction
{
    public function execute(Template $template, Certificate $certificate): void
    {
        CreatePDF::dispatch($template, $certificate);
    }
}
