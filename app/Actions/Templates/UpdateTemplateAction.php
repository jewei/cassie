<?php

namespace App\Actions\Templates;

use App\Models\Template;
use Illuminate\Support\Facades\Validator;

final readonly class UpdateTemplateAction
{
    public function __construct(
        private UpdateBackgroundAction $updateBackgroundAction
    ) {
    }

    public function execute(Template $template, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'properties' => ['array'],
            'properties.title' => ['required', 'string'],
        ])->validate();

        $template->fill([
            'name' => $input['name'],
            'properties' => $input['properties'],
        ]);

        $template->save();

        if (! empty($input['image'])) {
            $this->updateBackgroundAction->execute($template, $input['image']);
        }
    }
}
