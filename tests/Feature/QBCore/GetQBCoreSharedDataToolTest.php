<?php

use App\Mcp\Tools\QBCore\GetQBCoreSharedData;
use Laravel\Mcp\Request;

test('GetQBCoreSharedData returns Items data', function () {
    $tool = new GetQBCoreSharedData;
    $request = new Request(['data_type' => 'Items', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('Items');
    expect((string) $response->content())->toContain('label');
    expect((string) $response->content())->toContain('weight');
});

test('GetQBCoreSharedData returns Jobs data', function () {
    $tool = new GetQBCoreSharedData;
    $request = new Request(['data_type' => 'Jobs', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('Jobs');
    expect((string) $response->content())->toContain('grades');
    expect((string) $response->content())->toContain('Lua Example');
});

test('GetQBCoreSharedData returns Gangs data', function () {
    $tool = new GetQBCoreSharedData;
    $request = new Request(['data_type' => 'Gangs', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('Gangs');
});

test('GetQBCoreSharedData returns Vehicles data', function () {
    $tool = new GetQBCoreSharedData;
    $request = new Request(['data_type' => 'Vehicles', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('Vehicles');
    expect((string) $response->content())->toContain('model');
    expect((string) $response->content())->toContain('price');
});

test('GetQBCoreSharedData returns Weapons data', function () {
    $tool = new GetQBCoreSharedData;
    $request = new Request(['data_type' => 'Weapons', 'language' => 'js']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('Weapons');
    expect((string) $response->content())->toContain('JavaScript Example');
});

test('GetQBCoreSharedData returns StarterItems data', function () {
    $tool = new GetQBCoreSharedData;
    $request = new Request(['data_type' => 'StarterItems', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('StarterItems');
});

test('GetQBCoreSharedData returns Utilities data', function () {
    $tool = new GetQBCoreSharedData;
    $request = new Request(['data_type' => 'Utilities', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('Utilities');
    expect((string) $response->content())->toContain('RandomStr');
    expect((string) $response->content())->toContain('Trim');
});

test('GetQBCoreSharedData returns not found for invalid type', function () {
    $tool = new GetQBCoreSharedData;
    $request = new Request(['data_type' => 'InvalidType', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('not found');
});
