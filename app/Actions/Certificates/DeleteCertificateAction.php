<?php

namespace App\Actions\Certificates;

use App\Models\Certificate;
use Illuminate\Support\Facades\Storage;

final readonly class DeleteCertificateAction
{
    public function execute(Certificate $certificate): void
    {
        if ($certificate->exists) {
            Storage::disk($certificate->disk)->delete($certificate->filename);
        }

        $certificate->delete();
    }
}
