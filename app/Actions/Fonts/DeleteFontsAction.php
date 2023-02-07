<?php

namespace App\Actions\Fonts;

use Illuminate\Support\Facades\Storage;

final readonly class DeleteFontsAction
{
    /**
     * @return array<int, string>
     */
    public function execute(): array
    {
        return array_map(
            fn ($font) => ltrim($font, 'fonts/'),
            Storage::disk('local')->files('fonts')
        );
    }
}
