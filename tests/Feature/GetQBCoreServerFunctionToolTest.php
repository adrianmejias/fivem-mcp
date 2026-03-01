<?php

use App\Mcp\Tools\QBCore\Server\GetQBCoreServerFunction;
use Laravel\Mcp\Request;

it('returns qbcore server function info', function () {
    $tool = new GetQBCoreServerFunction;
    $request = new Request(['function_name' => 'QBCore.Functions.GetPlayer', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('GetPlayer');
});

it('returns not found for invalid server function', function () {
    $tool = new GetQBCoreServerFunction;
    $request = new Request(['function_name' => 'InvalidQBFunction', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('not found');
});

it('supports javascript examples for server functions', function () {
    $tool = new GetQBCoreServerFunction;
    $request = new Request(['function_name' => 'AddMoney', 'language' => 'js']);

    $response = $tool->handle($request);

    expect((string) $response->content())->not()->toBeEmpty();
});

it('handles case insensitive server function names', function () {
    $tool = new GetQBCoreServerFunction;
    $request = new Request(['function_name' => 'addmoney', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('AddMoney');
});

it('includes player data structure', function () {
    $tool = new GetQBCoreServerFunction;
    $request = new Request(['function_name' => 'GetMoney', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('GetMoney');
});
