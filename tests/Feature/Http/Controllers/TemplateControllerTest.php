<?php

use App\Models\Template;
use App\Models\User;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

it('can render the template listings page', function () {
    $user = User::factory()->create();
    $template = Template::factory()->create();

    $this
        ->actingAs($user)
        ->get('templates')
        ->assertStatus(200)
        ->assertSee($template->name);
});

it('can render the create template form', function () {
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->get('templates/create')
        ->assertInertia(
            fn ($page) => $page
                ->component('Template/Form')
                ->has('template')
                ->has('mode')
                ->where('mode', 'create')
        );
});

it('can create a new template', function () {
    $user = User::factory()->create();

    $post = [
        'name' => 'Foo Name',
        'properties' => [
            'title' => 'Foo Title',
        ],
    ];

    $this
        ->actingAs($user)
        ->post('templates', $post)
        ->assertSessionHasNoErrors()
        ->assertRedirect('templates');

    $data = [
        'name' => 'Foo Name',
        'properties->title' => 'Foo Title',
    ];

    assertDatabaseHas(Template::class, $data);
});

it('will not create a new template with invalid input', function () {
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->post('templates', [])
        ->assertSessionHasErrors(['name'])
        ->assertSessionHasErrors(['properties.title']);

    assertDatabaseCount(Template::class, 0);
});

it('can render the edit template form', function () {
    $user = User::factory()->create();
    $template = Template::factory()->create();

    $this
        ->actingAs($user)
        ->get('templates/'.$template->id.'/edit')
        ->assertInertia(
            fn ($page) => $page
                ->component('Template/Form')
                ->has('template')
                ->has('mode')
                ->where('mode', 'edit')
        );
});

it('can update a template', function () {
    $user = User::factory()->create();
    $template = Template::factory()->state(['name' => 'Foo Name'])->create();

    $post = [
        'name' => 'Bar Name',
        'properties' => [
            'title' => 'Bar Title',
        ],
    ];

    $this
        ->actingAs($user)
        ->patch('templates/'.$template->id, $post)
        ->assertSessionHasNoErrors()
        ->assertRedirect('templates');

    $data = [
        'name' => 'Bar Name',
        'properties' => $this->castAsJson(['title' => 'Bar Title']),
    ];

    assertDatabaseHas(Template::class, $data);
    assertDatabaseMissing(Template::class, ['name' => 'Foo Name']);
});

it('can delete a template', function () {
    $user = User::factory()->create();
    $template = Template::factory()->create();

    $this
        ->actingAs($user)
        ->delete('templates/'.$template->id)
        ->assertRedirect('templates');

    assertDatabaseCount(Template::class, 0);
});
