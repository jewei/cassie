<?php

namespace App\Jobs;

use App\Models\Certificate;
use App\Models\Template;
use App\Renderer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class CreatePDF implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private Template $template,
        private Certificate $certificate,
    ) {
    }

    public function handle(Renderer $renderer): void
    {
        $renderer->execute($this->template, $this->certificate);
    }

    public function uniqueId(): int
    {
        return $this->certificate->id;
    }
}
