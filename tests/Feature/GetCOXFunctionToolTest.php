<?php

use App\Mcp\Tools\GetCOXFunction;
use Laravel\Mcp\Request;

it('get cox function returns response with content', function () {
    $tool = new GetCOXFunction;
    $request = new Request(['function_name' => 'MySQL.query']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get cox function handles unknown function', function () {
    $tool = new GetCOXFunction;
    $request = new Request(['function_name' => 'NonExistentFunction123']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('not found') || expect($content)->toContain('COX');
});

it('get cox function works with insert method', function () {
    $tool = new GetCOXFunction;
    $request = new Request(['function_name' => 'MySQL.insert']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get cox function supports javascript examples', function () {
    $tool = new GetCOXFunction;
    $request = new Request(['function_name' => 'MySQL.query', 'language' => 'js']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get cox function works with transaction methods', function () {
    $tool = new GetCOXFunction;
    $request = new Request(['function_name' => 'MySQL.transaction.begin']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});
