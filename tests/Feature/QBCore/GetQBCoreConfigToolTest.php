<?php

use App\Mcp\Tools\QBCore\GetQBCoreConfig;
use Laravel\Mcp\Request;

test('GetQBCoreConfig returns Player config', function () {
    $tool = new GetQBCoreConfig;
    $request = new Request(['config_type' => 'Player', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('Player');
    expect((string) $response->content())->toContain('PlayerDefaults');
});

test('GetQBCoreConfig returns Framework config', function () {
    $tool = new GetQBCoreConfig;
    $request = new Request(['config_type' => 'Framework', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('Framework');
    expect((string) $response->content())->toContain('Version');
});

test('GetQBCoreConfig returns Database config', function () {
    $tool = new GetQBCoreConfig;
    $request = new Request(['config_type' => 'Database', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('Database');
    expect((string) $response->content())->toContain('Backend');
});

test('GetQBCoreConfig returns Callbacks config', function () {
    $tool = new GetQBCoreConfig;
    $request = new Request(['config_type' => 'Callbacks', 'language' => 'js']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('Callbacks');
    expect((string) $response->content())->toContain('JavaScript Example');
});

test('GetQBCoreConfig returns Features config', function () {
    $tool = new GetQBCoreConfig;
    $request = new Request(['config_type' => 'Features', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('Features');
});

test('GetQBCoreConfig returns RepTypes config', function () {
    $tool = new GetQBCoreConfig;
    $request = new Request(['config_type' => 'RepTypes', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('RepTypes');
    expect((string) $response->content())->toContain('selling');
});

test('GetQBCoreConfig returns not found for invalid type', function () {
    $tool = new GetQBCoreConfig;
    $request = new Request(['config_type' => 'InvalidType', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('not found');
});
