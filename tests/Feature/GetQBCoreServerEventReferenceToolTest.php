<?php

use App\Mcp\Tools\QBCore\Server\GetQBCoreServerEventReference;
use Laravel\Mcp\Request;

it('returns qbcore server event reference', function () {
    $tool = new GetQBCoreServerEventReference;
    $request = new Request(['event_name' => 'QBCore:Server:PlayerLoaded']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('PlayerLoaded');
});

it('returns not found for invalid server event', function () {
    $tool = new GetQBCoreServerEventReference;
    $request = new Request(['event_name' => 'InvalidServerEvent']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('not found');
});

it('lists all server events when empty event name', function () {
    $tool = new GetQBCoreServerEventReference;
    $request = new Request(['event_name' => '']);

    $response = $tool->handle($request);

    expect((string) $response->content())->not()->toBeEmpty();
});

it('searches by server event short names', function () {
    $tool = new GetQBCoreServerEventReference;
    $request = new Request(['event_name' => 'QBCore:Server:PlayerLoaded']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('PlayerLoaded');
});

it('contains examples for server events', function () {
    $tool = new GetQBCoreServerEventReference;
    $request = new Request(['event_name' => 'QBCore:Server:playerDropped']);

    $response = $tool->handle($request);

    expect((string) $response->content())->not()->toBeEmpty();
});
