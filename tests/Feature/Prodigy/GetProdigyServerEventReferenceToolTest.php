<?php

use App\Mcp\Tools\Prodigy\Server\GetProdigyServerEventReference;
use Laravel\Mcp\Request;

it('lists all server events when no event name given', function () {
    $tool = new GetProdigyServerEventReference;
    $request = new Request(['event_name' => '']);

    $response = $tool->handle($request);

    expect((string) $response->content())->not()->toBeEmpty();
});

it('returns server event reference for playerLoad', function () {
    $tool = new GetProdigyServerEventReference;
    $request = new Request(['event_name' => 'prp-bridge:server:playerLoad']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('playerLoad');
});

it('returns server event reference for playerUnload', function () {
    $tool = new GetProdigyServerEventReference;
    $request = new Request(['event_name' => 'prp-bridge:server:playerUnload']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('playerUnload');
});

it('returns server event reference for groupMemberAdded', function () {
    $tool = new GetProdigyServerEventReference;
    $request = new Request(['event_name' => 'prp-bridge:server:groupMemberAdded']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('groupMemberAdded');
});

it('returns server event reference for uniqueue party destroyed', function () {
    $tool = new GetProdigyServerEventReference;
    $request = new Request(['event_name' => 'prp-bridge:uniqueue:partyDestroyed']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('partyDestroyed');
});

it('returns not found for unknown server event', function () {
    $tool = new GetProdigyServerEventReference;
    $request = new Request(['event_name' => 'prp-bridge:server:nonexistentEvent']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('not found');
});
