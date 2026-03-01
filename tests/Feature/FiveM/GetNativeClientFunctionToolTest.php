<?php

use App\Mcp\Tools\FiveM\Client\GetNativeClientFunction;
use Laravel\Mcp\Request;

it('returns client native function info', function () {
    $tool = new GetNativeClientFunction;
    $request = new Request(['function_name' => 'PlayerPedId', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('PlayerPedId');
});

it('returns not found for invalid function', function () {
    $tool = new GetNativeClientFunction;
    $request = new Request(['function_name' => 'InvalidFunction', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('not found');
});

it('supports javascript examples', function () {
    $tool = new GetNativeClientFunction;
    $request = new Request(['function_name' => 'GetEntityCoords', 'language' => 'js']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('js');
});

it('handles case insensitive function names', function () {
    $tool = new GetNativeClientFunction;
    $request = new Request(['function_name' => 'playerpedid', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('PlayerPedId');
});

it('lists all client functions when no name provided', function () {
    $tool = new GetNativeClientFunction;
    $request = new Request(['function_name' => '', 'language' => 'lua']);

    $response = $tool->handle($request);

    expect((string) $response->content())->not()->toBeEmpty();
});
