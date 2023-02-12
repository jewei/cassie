<?php

use App\Models\Certificate;
use App\Models\Template;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\assertModelExists;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertIsString;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

it('has a generated uuid', function () {
    $certificate = Certificate::factory()
        ->for(Template::factory()->create())
        ->create();

    assertNotEmpty($certificate->uuid);
    assertIsString($certificate->uuid);
});

it('has a relationship that belongs to a template', function () {
    $certificate = Certificate::factory()
        ->for($template = Template::factory()->create())
        ->create();

    assertModelExists($certificate->template);
    assertSame($template->name, $certificate->template->name);
});

it('can tell if certificate exists', function () {
    $certificate = Certificate::factory()
        ->for(Template::factory()->create())
        ->create();

    assertFalse($certificate->file_exists);

    Storage::fake('local');
    Storage::put('certificates/'.$certificate->uuid.'.pdf', 'content');

    assertTrue($certificate->file_exists);
});

it('can tell the full path to the certificate', function () {
    $certificate = Certificate::factory()
        ->for(Template::factory()->create())
        ->create();

    assertSame('/var/www/html/storage/app/certificates/'.$certificate->uuid.'.pdf', $certificate->path);
});

it('can tell the storage disk that stores certificates', function () {
    $certificate = Certificate::factory()
        ->for(Template::factory()->create())
        ->create();

    assertSame('local', $certificate->disk);
});

it('can tell the relative path to the certificate', function () {
    $certificate = Certificate::factory()
        ->for(Template::factory()->create())
        ->create();

    assertSame('certificates/'.$certificate->uuid.'.pdf', $certificate->filename);
});

it('can compute a file name for download purpose', function () {
    $certificate = Certificate::factory()
        ->for(Template::factory()->state(['properties->title' => 'Title'])->create())
        ->state(['name' => 'Name'])
        ->create();

    assertSame('Title - Name.pdf', $certificate->download_filename);
});
