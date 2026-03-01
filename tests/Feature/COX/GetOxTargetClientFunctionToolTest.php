<?php

use App\Mcp\Tools\COX\Target\Client\GetOxTargetClientFunction;
use Laravel\Mcp\Request;

it('get ox_target client function returns response with content', function () {
    $tool = new GetOxTargetClientFunction;
    $request = new Request(['function_name' => 'addSphereZone']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get ox_target client function handles unknown function', function () {
    $tool = new GetOxTargetClientFunction;
    $request = new Request(['function_name' => 'NonExistentFunction123']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('not found');
});

it('get ox_target client function works with addGlobalVehicle', function () {
    $tool = new GetOxTargetClientFunction;
    $request = new Request(['function_name' => 'addGlobalVehicle']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get ox_target client function works with addModel', function () {
    $tool = new GetOxTargetClientFunction;
    $request = new Request(['function_name' => 'addModel']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get ox_target client function works with addBoxZone', function () {
    $tool = new GetOxTargetClientFunction;
    $request = new Request(['function_name' => 'addBoxZone']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get ox_target client function works with addPolyZone', function () {
    $tool = new GetOxTargetClientFunction;
    $request = new Request(['function_name' => 'addPolyZone']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get ox_target client function works with isActive', function () {
    $tool = new GetOxTargetClientFunction;
    $request = new Request(['function_name' => 'isActive']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get ox_target client function supports javascript examples', function () {
    $tool = new GetOxTargetClientFunction;
    $request = new Request(['function_name' => 'addEntity', 'language' => 'js']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});
