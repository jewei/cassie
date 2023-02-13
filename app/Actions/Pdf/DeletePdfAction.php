<?php

namespace App\Actions\Pdf;

use App\Models\Certificate;
use Illuminate\Support\Facades\Storage;

final readonly class DeletePdfAction
{
    public function execute(Certificate $certificate): void
    {
        if ($certificate->file_exists) {
            Storage::disk($certificate->disk)->delete($certificate->filename);
        }
    }
}
