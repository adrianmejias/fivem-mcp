<?php

use App\Mcp\Tools\GetEventReference;
use Laravel\Mcp\Request;

it('get event reference returns response with content', function () {
    $tool = new GetEventReference();
    $request = new Request(['event_name' => 'playerSpawned']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get event reference handles unknown event', function () {
    $tool = new GetEventReference();
    $request = new Request(['event_name' => 'NonExistentEvent123']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('not found') || expect($content)->toContain('Event');
});
