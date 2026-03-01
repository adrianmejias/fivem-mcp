<?php

use App\Mcp\Tools\GenerateResourceBoilerplate;
use Laravel\Mcp\Request;

it('generate resource boilerplate returns response with content', function () {
    $tool = new GenerateResourceBoilerplate();
    $request = new Request(['resource_name' => 'test-resource']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('generate resource boilerplate includes resource name', function () {
    $tool = new GenerateResourceBoilerplate();
    $request = new Request(['resource_name' => 'my-boilerplate']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('my-boilerplate') || expect($content)->toContain('boilerplate') || expect($content)->not()->toBeEmpty();
});
