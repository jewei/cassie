<?php

use App\Models\Certificate;
use App\Models\Template;

it('can create models with relationships', function () {
    $template = Template::factory()
        ->has(Certificate::factory()->count(5))
        ->create();

    expect($template->certificates)->toHaveCount(5);
});
