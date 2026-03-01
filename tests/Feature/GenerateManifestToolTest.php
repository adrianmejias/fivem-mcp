<?php

use App\Mcp\Tools\GenerateManifest;
use Laravel\Mcp\Request;

it('generate manifest returns response with content', function () {
    $tool = new GenerateManifest();
    $request = new Request(['resource_name' => 'test-resource']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('generate manifest includes required fields', function () {
    $tool = new GenerateManifest();
    $request = new Request(['resource_name' => 'my-test-resource']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain("fx_version 'cerulean'");
    expect($content)->toContain("game 'gta5'");
    expect($content)->toContain("lua54 'yes'");
});
