<?php

use App\Mcp\Tools\FiveM\Server\GetEventServerReference;
use Laravel\Mcp\Request;

it('returns server event reference', function () {
    $tool = new GetEventServerReference;
    $request = new Request(['event_name' => 'playerConnecting']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('playerConnecting');
});

it('returns not found for invalid server event', function () {
    $tool = new GetEventServerReference;
    $request = new Request(['event_name' => 'InvalidServerEvent']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('not found');
});

it('lists all server events when empty event name', function () {
    $tool = new GetEventServerReference;
    $request = new Request(['event_name' => '']);

    $response = $tool->handle($request);

    expect((string) $response->content())->not()->toBeEmpty();
});

it('handles case insensitive server event names', function () {
    $tool = new GetEventServerReference;
    $request = new Request(['event_name' => 'playerdropped']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('playerDropped');
});

it('contains examples for server events', function () {
    $tool = new GetEventServerReference;
    $request = new Request(['event_name' => 'playerConnecting']);

    $response = $tool->handle($request);

    expect((string) $response->content())->not()->toBeEmpty();
});
