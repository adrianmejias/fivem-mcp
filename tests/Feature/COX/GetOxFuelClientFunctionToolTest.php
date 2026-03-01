<?php

use App\Mcp\Tools\COX\Fuel\Client\GetOxFuelClientFunction;
use Laravel\Mcp\Request;

it('get ox_fuel client function returns response with content', function () {
    $tool = new GetOxFuelClientFunction;
    $request = new Request(['function_name' => 'setMoneyCheck']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get ox_fuel client function handles unknown function', function () {
    $tool = new GetOxFuelClientFunction;
    $request = new Request(['function_name' => 'NonExistentFunction123']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('not found');
});

it('get ox_fuel client function supports javascript examples', function () {
    $tool = new GetOxFuelClientFunction;
    $request = new Request(['function_name' => 'setMoneyCheck', 'language' => 'js']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});
