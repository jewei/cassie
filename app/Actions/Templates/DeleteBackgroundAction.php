<?php

namespace App\Actions\Templates;

use App\Models\Template;
use Illuminate\Support\Facades\Storage;

final readonly class DeleteBackgroundAction
{
    public function execute(Template $template): void
    {
        if ($template->image) {
            Storage::delete($template->image);
        }

        if ($template->thumbnail) {
            Storage::disk('public')->delete($template->thumbnail);
        }

        $template->image = '';
        $template->thumbnail = '';

        $template->save();
    }
}
