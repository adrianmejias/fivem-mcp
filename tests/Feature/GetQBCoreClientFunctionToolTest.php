<?php

use App\Mcp\Tools\QBCore\Client\GetQBCoreClientFunction;
use Laravel\Mcp\Request;

it('returns qbcore client function info', function () {
    $tool = new GetQBCoreClientFunction;
    $request = new Request(['function_name' => 'QBCore.Functions.GetPlayerData', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('GetPlayerData');
});

it('returns not found for invalid client function', function () {
    $tool = new GetQBCoreClientFunction;
    $request = new Request(['function_name' => 'InvalidClientFunction', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('not found');
});

it('supports javascript examples for client functions', function () {
    $tool = new GetQBCoreClientFunction;
    $request = new Request(['function_name' => 'QBCore.Commands.Add', 'language' => 'js']);

    $response = $tool->handle($request);

    expect((string) $response->content())->not()->toBeEmpty();
});

it('handles case insensitive client function names', function () {
    $tool = new GetQBCoreClientFunction;
    $request = new Request(['function_name' => 'qbcore.functions.getplayerdata', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('GetPlayerData');
});

it('includes command registration function', function () {
    $tool = new GetQBCoreClientFunction;
    $request = new Request(['function_name' => 'QBCore.Commands.Add', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->not()->toBeEmpty();
});
