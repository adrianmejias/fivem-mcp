<?php

use App\Mcp\Tools\COX\Doorlock\Client\GetOxDoorlockClientFunction;
use Laravel\Mcp\Request;

it('get ox_doorlock client function returns response with content', function () {
    $tool = new GetOxDoorlockClientFunction;
    $request = new Request(['function_name' => 'getClosestDoor']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get ox_doorlock client function handles unknown function', function () {
    $tool = new GetOxDoorlockClientFunction;
    $request = new Request(['function_name' => 'NonExistentFunction123']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('not found');
});

it('get ox_doorlock client function works with pickClosestDoor', function () {
    $tool = new GetOxDoorlockClientFunction;
    $request = new Request(['function_name' => 'pickClosestDoor']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get ox_doorlock client function works with useClosestDoor', function () {
    $tool = new GetOxDoorlockClientFunction;
    $request = new Request(['function_name' => 'useClosestDoor']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get ox_doorlock client function supports javascript examples', function () {
    $tool = new GetOxDoorlockClientFunction;
    $request = new Request(['function_name' => 'getClosestDoor', 'language' => 'js']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});
