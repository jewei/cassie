<?php

namespace App\Actions\Fonts;

use Illuminate\Support\Facades\Storage;

final readonly class DeleteFontsAction
{
    public function execute(string $filename): void
    {
        if (empty($filename)) {
            return;
        }

        if (Storage::disk('local')->exists('fonts/' . $filename)) {
            Storage::disk('local')->delete('fonts/' . $filename);
        }
    }
}
