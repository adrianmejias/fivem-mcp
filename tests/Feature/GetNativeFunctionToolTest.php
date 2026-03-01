<?php

use App\Mcp\Tools\GetNativeFunction;
use Laravel\Mcp\Request;

it('get native function returns response with content', function () {
    $tool = new GetNativeFunction();
    $request = new Request(['function_name' => 'GetPlayerPed']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get native function finds known function', function () {
    $tool = new GetNativeFunction();
    $request = new Request(['function_name' => 'GetPlayerPed']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('GetPlayerPed') || expect($content)->toContain('player');
});

it('get native function handles unknown function', function () {
    $tool = new GetNativeFunction();
    $request = new Request(['function_name' => 'UnknownFunction123']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('not found');
});
