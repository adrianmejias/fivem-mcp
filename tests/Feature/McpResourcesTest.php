<?php

use App\Mcp\Resources\BestPractices;
use App\Mcp\Resources\CodeSnippets;
use App\Mcp\Resources\FrameworkComparison;
use Laravel\Mcp\Request;

it('code snippets resource returns response with content', function () {
    $resource = new CodeSnippets;
    $request = new Request([]);

    $response = $resource->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('code snippets response contains content', function () {
    $resource = new CodeSnippets;
    $request = new Request([]);

    $response = $resource->handle($request);
    $content = (string) $response->content();

    expect($content)->not()->toBeEmpty();
});

it('best practices resource returns response with content', function () {
    $resource = new BestPractices;
    $request = new Request([]);

    $response = $resource->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('best practices response contains content', function () {
    $resource = new BestPractices;
    $request = new Request([]);

    $response = $resource->handle($request);
    $content = (string) $response->content();

    expect($content)->not()->toBeEmpty();
});

it('framework comparison resource returns response with content', function () {
    $resource = new FrameworkComparison;
    $request = new Request([]);

    $response = $resource->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('framework comparison response contains content', function () {
    $resource = new FrameworkComparison;
    $request = new Request([]);

    $response = $resource->handle($request);
    $content = (string) $response->content();

    expect($content)->not()->toBeEmpty();
});
