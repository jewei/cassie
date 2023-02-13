<?php

namespace App\Actions\Participants;

use App\Actions\Certificates\DeleteCertificateAction;
use App\Actions\Pdf\RegenerateTemplatePdfsAction;
use App\Models\Template;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

final readonly class UpdateParticipantsAction
{
    public function __construct(
        private DeleteCertificateAction $deleteCertificateAction,
        private RegenerateTemplatePdfsAction $regenerateTemplatePdfsAction,
    ) {
    }

    /**
     * @param  array<string, string>  $input
     */
    public function execute(Template $template, array $input): void
    {
        Validator::make($input, [
            'datatext' => ['required', 'string', ''],
        ])->validate();

        $lines = explode("\n", $input['datatext']);

        $template->certificates->each(function ($certificate) {
            $this->deleteCertificateAction->execute($certificate);
        });

        DB::transaction(function () use ($lines, $template) {
            foreach ($lines as $line) {
                try {
                    $this->saveParticipant($template, ...array_map('trim', str_getcsv($line)));
                } catch (\Throwable) {
                    throw new InvalidParticipantException('Invalid participant: '.$line);
                }
            }
        });

        $template->refresh();
        $this->regenerateTemplatePdfsAction->execute($template);
    }

    private function saveParticipant(Template $template, string $name, string $email): void
    {
        $template->certificates()->create([
            'name' => $name,
            'email' => $email,
        ]);
    }
}
