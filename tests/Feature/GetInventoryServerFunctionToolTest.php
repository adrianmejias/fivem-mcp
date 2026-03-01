<?php

use App\Mcp\Tools\COX\Inventory\Server\GetInventoryServerFunction;
use Laravel\Mcp\Request;

it('get inventory server function returns response with content', function () {
    $tool = new GetInventoryServerFunction;
    $request = new Request(['function_name' => 'AddItem']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get inventory server function handles unknown function', function () {
    $tool = new GetInventoryServerFunction;
    $request = new Request(['function_name' => 'NonExistentFunction123']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('not found');
});

it('get inventory server function works with RemoveItem', function () {
    $tool = new GetInventoryServerFunction;
    $request = new Request(['function_name' => 'RemoveItem']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get inventory server function works with RegisterStash', function () {
    $tool = new GetInventoryServerFunction;
    $request = new Request(['function_name' => 'RegisterStash']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get inventory server function supports javascript examples', function () {
    $tool = new GetInventoryServerFunction;
    $request = new Request(['function_name' => 'GetItem', 'language' => 'js']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get inventory server function works with SetDurability', function () {
    $tool = new GetInventoryServerFunction;
    $request = new Request(['function_name' => 'SetDurability']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});
