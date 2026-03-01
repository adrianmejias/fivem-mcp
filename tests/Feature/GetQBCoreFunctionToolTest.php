<?php

use App\Mcp\Tools\GetQBCoreFunction;
use Laravel\Mcp\Request;

it('get qbcore function returns response with content', function () {
    $tool = new GetQBCoreFunction();
    $request = new Request(['function_name' => 'QBCore.Functions.GetPlayer']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get qbcore function handles unknown function', function () {
    $tool = new GetQBCoreFunction();
    $request = new Request(['function_name' => 'NonExistentFunction123']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('not found') || expect($content)->toContain('QBCore');
});

it('get qbcore function works with shorthand names', function () {
    $tool = new GetQBCoreFunction();
    $request = new Request(['function_name' => 'AddMoney']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get qbcore function supports javascript examples', function () {
    $tool = new GetQBCoreFunction();
    $request = new Request(['function_name' => 'AddMoney', 'language' => 'js']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});
