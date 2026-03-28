<?php

use App\Mcp\Tools\Prodigy\Client\GetProdigyClientEventReference;
use Laravel\Mcp\Request;

it('lists all client events when no event name given', function () {
    $tool = new GetProdigyClientEventReference;
    $request = new Request(['event_name' => '']);

    $response = $tool->handle($request);

    expect((string) $response->content())->not()->toBeEmpty();
});

it('returns client event reference for prp-bridge:notify', function () {
    $tool = new GetProdigyClientEventReference;
    $request = new Request(['event_name' => 'prp-bridge:notify']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('prp-bridge:notify');
});

it('returns client event reference for sound play event', function () {
    $tool = new GetProdigyClientEventReference;
    $request = new Request(['event_name' => 'prp-bridge:sound:play']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('prp-bridge:sound:play');
});

it('returns client event reference for allowlist update event', function () {
    $tool = new GetProdigyClientEventReference;
    $request = new Request(['event_name' => 'prp-bridge:client:updateAllowlist']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('updateAllowlist');
});

it('returns client event reference for revived event', function () {
    $tool = new GetProdigyClientEventReference;
    $request = new Request(['event_name' => 'prp-bridge:client:revived']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('revived');
});

it('returns not found for unknown client event', function () {
    $tool = new GetProdigyClientEventReference;
    $request = new Request(['event_name' => 'prp-bridge:nonexistent:event']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('not found');
});
