<?php

namespace App\Actions\Templates;

use App\Models\Template;
use Illuminate\Database\Eloquent\Collection;

final readonly class FetchTemplatesAction
{
    /**
     * @return Collection<int, Template>
     */
    public function execute(): Collection
    {
        return Template::query()
            ->orderByDesc('updated_at')
            ->withCount('certificates')
            ->get();
    }
}
