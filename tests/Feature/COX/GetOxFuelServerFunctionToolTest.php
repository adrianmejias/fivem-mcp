<?php

use App\Mcp\Tools\COX\Fuel\Server\GetOxFuelServerFunction;
use Laravel\Mcp\Request;

it('get ox_fuel server function returns response with content', function () {
    $tool = new GetOxFuelServerFunction;
    $request = new Request(['function_name' => 'setPaymentMethod']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get ox_fuel server function handles unknown function', function () {
    $tool = new GetOxFuelServerFunction;
    $request = new Request(['function_name' => 'NonExistentFunction123']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('not found');
});

it('get ox_fuel server function supports javascript examples', function () {
    $tool = new GetOxFuelServerFunction;
    $request = new Request(['function_name' => 'setPaymentMethod', 'language' => 'js']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});
