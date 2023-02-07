<?php

namespace App\Actions\Fonts;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final readonly class SaveFontAction
{
    public function execute(UploadedFile $file): void
    {
        Storage::putFileAs(
            path: 'fonts',
            file: $file,
            name: $file->getClientOriginalName()
        );
    }
}
