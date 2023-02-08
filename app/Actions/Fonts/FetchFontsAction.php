<?php

namespace App\Actions\Fonts;

use Illuminate\Support\Facades\Storage;

final readonly class FetchFontsAction
{
    /**
     * @return array<int, string>
     */
    public function execute(): array
    {
        return array_map(
            fn (string $font) => ltrim($font, 'fonts/'),
            Storage::disk('local')->files('fonts')
        );
    }
}
