<?php

namespace App\Actions\Templates;

use App\Models\Template;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;

final readonly class UpdateBackgroundAction
{
    public function __construct(
        private DeleteBackgroundAction $deleteBackgroundAction,
        private ImageManagerStatic $imageManager
    ) {
    }

    public function execute(Template $template, UploadedFile $image): void
    {
        $this->deleteBackgroundAction->execute($template);

        $template->image = (string) Storage::putFile('backgrounds', $image);

        $parts = explode(DIRECTORY_SEPARATOR, $template->image);
        $template->thumbnail = 'thumbnails/'.last($parts);

        Storage::disk('public')->put(
            $template->thumbnail,
            $this->imageManager
                ->make(Storage::path($template->image))
                ->resize(300, 200)
                ->encode()
        );

        $template->save();
    }
}
