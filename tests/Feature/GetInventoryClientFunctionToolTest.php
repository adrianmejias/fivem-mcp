<?php

use App\Mcp\Tools\COX\Inventory\Client\GetInventoryClientFunction;
use Laravel\Mcp\Request;

it('get inventory client function returns response with content', function () {
    $tool = new GetInventoryClientFunction;
    $request = new Request(['function_name' => 'openInventory']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get inventory client function handles unknown function', function () {
    $tool = new GetInventoryClientFunction;
    $request = new Request(['function_name' => 'NonExistentFunction123']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('not found');
});

it('get inventory client function works with SearchPlayer', function () {
    $tool = new GetInventoryClientFunction;
    $request = new Request(['function_name' => 'SearchPlayer']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get inventory client function works with useItem', function () {
    $tool = new GetInventoryClientFunction;
    $request = new Request(['function_name' => 'useItem']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get inventory client function supports javascript examples', function () {
    $tool = new GetInventoryClientFunction;
    $request = new Request(['function_name' => 'GetCurrentWeapon', 'language' => 'js']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get inventory client function works with getCurrentWeight', function () {
    $tool = new GetInventoryClientFunction;
    $request = new Request(['function_name' => 'getCurrentWeight']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});
