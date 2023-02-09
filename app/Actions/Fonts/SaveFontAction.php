<?php

namespace App\Actions\Fonts;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final readonly class SaveFontAction
{
    /**
     * @param  null|array<int, UploadedFile>|UploadedFile  $file
     */
    public function execute($file): void
    {
        if (empty($file)) {
            return;
        }

        if (is_array($file)) {
            $file = $file[0];
        }

        Storage::putFileAs(
            path: 'fonts',
            file: $file,
            name: $file->getClientOriginalName()
        );
    }
}
