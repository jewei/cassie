<?php

namespace App\Actions\Participants;

use App\Models\Certificate;
use App\Models\Template;
use Illuminate\Database\Eloquent\Collection;

final readonly class FetchParticipantsAction
{
    /**
     * @return Collection<int, Certificate>
     */
    public function execute(Template $template): Collection
    {
        return $template->certificates()
            ->orderBy('name')
            ->get();
    }
}
