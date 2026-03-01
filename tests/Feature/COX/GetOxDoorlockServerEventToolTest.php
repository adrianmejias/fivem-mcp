<?php

use App\Mcp\Tools\COX\Doorlock\Server\GetOxDoorlockServerEvent;
use Laravel\Mcp\Request;

it('get ox_doorlock server event returns response with content', function () {
    $tool = new GetOxDoorlockServerEvent;
    $request = new Request(['event_name' => 'ox_doorlock:stateChanged']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('get ox_doorlock server event handles unknown event', function () {
    $tool = new GetOxDoorlockServerEvent;
    $request = new Request(['event_name' => 'NonExistentEvent123']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('not found');
});

it('get ox_doorlock server event supports javascript examples', function () {
    $tool = new GetOxDoorlockServerEvent;
    $request = new Request(['event_name' => 'ox_doorlock:stateChanged', 'language' => 'js']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});
