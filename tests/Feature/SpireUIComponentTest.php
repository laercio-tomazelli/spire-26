<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

test('spire-ui button component renders correctly', function (): void {
    $rendered = Blade::render('<x-spire::button>Test Button</x-spire::button>');

    expect($rendered)
        ->toContain('button')
        ->toContain('Test Button');
});

test('spire-ui input component renders correctly', function (): void {
    $rendered = Blade::render('<x-spire::input name="email" label="Email" />');

    expect($rendered)
        ->toContain('input')
        ->toContain('Email')
        ->toContain('name="email"');
});

test('spire-ui alert component renders correctly', function (): void {
    $rendered = Blade::render('<x-spire::alert type="success">Success message</x-spire::alert>');

    expect($rendered)
        ->toContain('Success message');
});
