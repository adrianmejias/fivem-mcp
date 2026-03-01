<?php

use App\Mcp\Tools\COX\MySQL\GetCOXEventReference;
use Laravel\Mcp\Request;

it('get cox event reference returns response with content', function () {
    $tool = new GetCOXEventReference;
    $request = new Request(['event_name' => 'coxMySQL:connected']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get cox event reference handles unknown event', function () {
    $tool = new GetCOXEventReference;
    $request = new Request(['event_name' => 'NonExistentCOXEvent123']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('not found') || expect($content)->toContain('COX event');
});

it('get cox event reference filters by type', function () {
    $tool = new GetCOXEventReference;
    $request = new Request(['event_type' => 'connection']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get cox event reference lists all events', function () {
    $tool = new GetCOXEventReference;
    $request = new Request(['event_type' => 'all']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get cox event reference supports query events', function () {
    $tool = new GetCOXEventReference;
    $request = new Request(['event_name' => 'coxMySQL:queryCompleted']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});
