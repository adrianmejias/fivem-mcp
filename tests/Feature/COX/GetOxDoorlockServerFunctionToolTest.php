<?php

use App\Mcp\Tools\COX\Doorlock\Server\GetOxDoorlockServerFunction;
use Laravel\Mcp\Request;

it('get ox_doorlock server function returns response with content', function () {
    $tool = new GetOxDoorlockServerFunction;
    $request = new Request(['function_name' => 'getDoor']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get ox_doorlock server function handles unknown function', function () {
    $tool = new GetOxDoorlockServerFunction;
    $request = new Request(['function_name' => 'NonExistentFunction123']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('not found');
});

it('get ox_doorlock server function works with getDoorFromName', function () {
    $tool = new GetOxDoorlockServerFunction;
    $request = new Request(['function_name' => 'getDoorFromName']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get ox_doorlock server function works with editDoor', function () {
    $tool = new GetOxDoorlockServerFunction;
    $request = new Request(['function_name' => 'editDoor']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get ox_doorlock server function works with setDoorState', function () {
    $tool = new GetOxDoorlockServerFunction;
    $request = new Request(['function_name' => 'setDoorState']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get ox_doorlock server function supports javascript examples', function () {
    $tool = new GetOxDoorlockServerFunction;
    $request = new Request(['function_name' => 'getDoor', 'language' => 'js']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});
