<?php

use App\Mcp\Tools\GetQBCoreEventReference;
use Laravel\Mcp\Request;

it('get qbcore event reference returns response with content', function () {
    $tool = new GetQBCoreEventReference();
    $request = new Request(['event_name' => 'QBCore:Client:OnPlayerLoaded']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get qbcore event reference handles unknown event', function () {
    $tool = new GetQBCoreEventReference();
    $request = new Request(['event_name' => 'NonExistentQBCoreEvent123']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('not found') || expect($content)->toContain('QBCore event');
});

it('get qbcore event reference filters by category', function () {
    $tool = new GetQBCoreEventReference();
    $request = new Request(['event_category' => 'player']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});
