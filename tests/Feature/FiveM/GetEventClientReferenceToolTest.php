<?php

use App\Mcp\Tools\FiveM\Client\GetEventClientReference;
use Laravel\Mcp\Request;

it('returns client event reference', function () {
    $tool = new GetEventClientReference;
    $request = new Request(['event_name' => 'onResourceStart']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('onResourceStart');
});

it('returns not found for invalid event', function () {
    $tool = new GetEventClientReference;
    $request = new Request(['event_name' => 'InvalidEvent']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('not found');
});

it('lists all client events when empty event name', function () {
    $tool = new GetEventClientReference;
    $request = new Request(['event_name' => '']);

    $response = $tool->handle($request);

    expect((string) $response->content())->not()->toBeEmpty();
});

it('handles case insensitive event names', function () {
    $tool = new GetEventClientReference;
    $request = new Request(['event_name' => 'onresourcestart']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('onResourceStart');
});

it('contains lua examples', function () {
    $tool = new GetEventClientReference;
    $request = new Request(['event_name' => 'onResourceStart']);

    $response = $tool->handle($request);

    expect((string) $response->content())->not()->toBeEmpty();
});
