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

// Radio Component Tests
test('spire-ui radio component renders correctly', function (): void {
    $rendered = Blade::render('<x-spire::radio name="gender" value="male" label="Masculino" />');

    expect($rendered)
        ->toContain('type="radio"')
        ->toContain('name="gender"')
        ->toContain('value="male"')
        ->toContain('Masculino');
});

test('spire-ui radio component renders checked state', function (): void {
    $rendered = Blade::render('<x-spire::radio name="option" value="1" label="Option 1" checked />');

    expect($rendered)
        ->toContain('checked');
});

test('spire-ui radio component renders disabled state', function (): void {
    $rendered = Blade::render('<x-spire::radio name="option" value="1" label="Option 1" disabled />');

    expect($rendered)
        ->toContain('disabled');
});

test('spire-ui radio component renders with description', function (): void {
    $rendered = Blade::render('<x-spire::radio name="plan" value="basic" label="Basic Plan" description="For small teams" />');

    expect($rendered)
        ->toContain('Basic Plan')
        ->toContain('For small teams');
});

test('spire-ui radio-group component renders correctly', function (): void {
    $rendered = Blade::render('
        <x-spire::radio-group name="priority" label="Priority">
            <x-spire::radio value="low" label="Low" />
            <x-spire::radio value="high" label="High" />
        </x-spire::radio-group>
    ');

    expect($rendered)
        ->toContain('Priority')
        ->toContain('Low')
        ->toContain('High');
});

// Table Component Tests
test('spire-ui table component renders correctly', function (): void {
    $rendered = Blade::render('<x-spire::table :data="[]" />');

    expect($rendered)
        ->toContain('table')
        ->toContain('Nenhum resultado encontrado');
});

test('spire-ui table component renders with searchable', function (): void {
    $rendered = Blade::render('<x-spire::table :data="[]" searchable />');

    expect($rendered)
        ->toContain('Buscar')
        ->toContain('input');
});

test('spire-ui table component renders with pagination', function (): void {
    $rendered = Blade::render('<x-spire::table :data="[]" paginated />');

    expect($rendered)
        ->toContain('Anterior')
        ->toContain('Próximo');
});

test('spire-ui table-column component renders correctly', function (): void {
    $rendered = Blade::render('<x-spire::table-column field="name" label="Nome" />');

    expect($rendered)
        ->toContain('th')
        ->toContain('Nome');
});

test('spire-ui table-column component renders sortable', function (): void {
    $rendered = Blade::render('<x-spire::table-column field="name" label="Nome" sortable />');

    expect($rendered)
        ->toContain('button')
        ->toContain('Nome')
        ->toContain('@click="sort');
});

// Toast Component Tests
test('spire-ui toast component renders correctly', function (): void {
    $rendered = Blade::render('<x-spire::toast message="Test message" />');

    expect($rendered)
        ->toContain('Test message')
        ->toContain('role="alert"');
});

test('spire-ui toast component renders success type', function (): void {
    $rendered = Blade::render('<x-spire::toast message="Success!" type="success" />');

    expect($rendered)
        ->toContain('Success!')
        ->toContain('bg-green');
});

test('spire-ui toast component renders error type', function (): void {
    $rendered = Blade::render('<x-spire::toast message="Error!" type="error" />');

    expect($rendered)
        ->toContain('Error!')
        ->toContain('bg-red');
});

test('spire-ui toast component renders warning type', function (): void {
    $rendered = Blade::render('<x-spire::toast message="Warning!" type="warning" />');

    expect($rendered)
        ->toContain('Warning!')
        ->toContain('bg-yellow');
});

test('spire-ui toast component renders dismissible button', function (): void {
    $rendered = Blade::render('<x-spire::toast message="Dismissible" dismissible />');

    expect($rendered)
        ->toContain('Fechar')
        ->toContain('@click="close()"');
});

test('spire-ui toast component renders without dismiss button', function (): void {
    $rendered = Blade::render('<x-spire::toast message="Not dismissible" :dismissible="false" />');

    expect($rendered)
        ->toContain('Not dismissible')
        ->not->toContain('Fechar');
});

test('spire-ui toast-container component renders correctly', function (): void {
    $rendered = Blade::render('<x-spire::toast-container position="top-right" />');

    expect($rendered)
        ->toContain('fixed')
        ->toContain('top-4')
        ->toContain('right-4');
});

test('spire-ui toast-container component renders bottom-left position', function (): void {
    $rendered = Blade::render('<x-spire::toast-container position="bottom-left" />');

    expect($rendered)
        ->toContain('bottom-4')
        ->toContain('left-4');
});

// Checkbox Component Tests (existing component - additional tests)
test('spire-ui checkbox component renders correctly', function (): void {
    $rendered = Blade::render('<x-spire::checkbox name="terms" label="Accept terms" />');

    expect($rendered)
        ->toContain('type="checkbox"')
        ->toContain('name="terms"')
        ->toContain('Accept terms');
});

test('spire-ui checkbox component renders with description', function (): void {
    $rendered = Blade::render('<x-spire::checkbox name="newsletter" label="Subscribe" description="Get weekly updates" />');

    expect($rendered)
        ->toContain('Subscribe')
        ->toContain('Get weekly updates');
});

// Textarea Component Tests (existing component - additional tests)
test('spire-ui textarea component renders correctly', function (): void {
    $rendered = Blade::render('<x-spire::textarea name="message" label="Message" />');

    expect($rendered)
        ->toContain('textarea')
        ->toContain('name="message"')
        ->toContain('Message');
});

test('spire-ui textarea component renders with rows', function (): void {
    $rendered = Blade::render('<x-spire::textarea name="bio" label="Bio" :rows="5" />');

    expect($rendered)
        ->toContain('rows="5"');
});
