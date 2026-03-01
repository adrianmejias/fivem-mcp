<?php

use App\Mcp\Tools\QBCore\GetQBCorePlayers;
use Laravel\Mcp\Request;

test('GetQBCorePlayers returns Structure info', function () {
    $tool = new GetQBCorePlayers;
    $request = new Request(['info_type' => 'Structure', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('Structure');
    expect((string) $response->content())->toContain('PlayerData');
    expect((string) $response->content())->toContain('Functions');
});

test('GetQBCorePlayers returns PlayerData info', function () {
    $tool = new GetQBCorePlayers;
    $request = new Request(['info_type' => 'PlayerData', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('PlayerData');
    expect((string) $response->content())->toContain('citizenid');
    expect((string) $response->content())->toContain('charinfo');
});

test('GetQBCorePlayers returns Methods info', function () {
    $tool = new GetQBCorePlayers;
    $request = new Request(['info_type' => 'Methods', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('Methods');
    expect((string) $response->content())->toContain('AddItem');
    expect((string) $response->content())->toContain('SetJob');
});

test('GetQBCorePlayers returns Events info', function () {
    $tool = new GetQBCorePlayers;
    $request = new Request(['info_type' => 'Events', 'language' => 'js']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('Events');
    expect((string) $response->content())->toContain('JavaScript Example');
});

test('GetQBCorePlayers returns Examples info', function () {
    $tool = new GetQBCorePlayers;
    $request = new Request(['info_type' => 'Examples', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('Examples');
    expect((string) $response->content())->toContain('HasItem');
});

test('GetQBCorePlayers returns not found for invalid type', function () {
    $tool = new GetQBCorePlayers;
    $request = new Request(['info_type' => 'InvalidType', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('not found');
});
