<?php

use App\Mcp\Tools\FiveM\Server\GetNativeServerFunction;
use Laravel\Mcp\Request;

it('returns server native function info', function () {
    $tool = new GetNativeServerFunction;
    $request = new Request(['function_name' => 'TriggerClientEvent', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('TriggerClientEvent');
});

it('returns not found for invalid function', function () {
    $tool = new GetNativeServerFunction;
    $request = new Request(['function_name' => 'InvalidServerFunction', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('not found');
});

it('supports javascript examples for server natives', function () {
    $tool = new GetNativeServerFunction;
    $request = new Request(['function_name' => 'GetPlayerName', 'language' => 'js']);

    $response = $tool->handle($request);

    expect((string) $response->content())->not()->toBeEmpty();
});

it('handles case insensitive server function names', function () {
    $tool = new GetNativeServerFunction;
    $request = new Request(['function_name' => 'getplayername', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('GetPlayerName');
});

it('lists all server functions when no name provided', function () {
    $tool = new GetNativeServerFunction;
    $request = new Request(['function_name' => '', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->not()->toBeEmpty();
});
