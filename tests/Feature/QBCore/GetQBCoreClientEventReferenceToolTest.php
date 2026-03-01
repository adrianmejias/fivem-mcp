<?php

use App\Mcp\Tools\QBCore\Client\GetQBCoreClientEventReference;
use Laravel\Mcp\Request;

it('returns qbcore client event reference', function () {
    $tool = new GetQBCoreClientEventReference;
    $request = new Request(['event_name' => 'QBCore:Client:OnPlayerLoad']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('OnPlayerLoad');
});

it('returns not found for invalid client event', function () {
    $tool = new GetQBCoreClientEventReference;
    $request = new Request(['event_name' => 'InvalidClientEvent']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('not found');
});

it('lists all client events when empty event name', function () {
    $tool = new GetQBCoreClientEventReference;
    $request = new Request(['event_name' => '']);

    $response = $tool->handle($request);

    expect((string) $response->content())->not()->toBeEmpty();
});

it('searches by client event short names', function () {
    $tool = new GetQBCoreClientEventReference;
    $request = new Request(['event_name' => 'QBCore:Client:OnPlayerLoad']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('OnPlayerLoad');
});

it('includes notification event', function () {
    $tool = new GetQBCoreClientEventReference;
    $request = new Request(['event_name' => 'QBCore:Notify']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('Notify');
});
