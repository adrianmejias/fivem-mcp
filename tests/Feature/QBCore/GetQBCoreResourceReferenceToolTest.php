<?php

use App\Mcp\Tools\QBCore\GetQBCoreResourceReference;
use Laravel\Mcp\Request;

test('GetQBCoreResourceReference returns ambulance job details', function () {
    $tool = new GetQBCoreResourceReference;
    $request = new Request(['resource_name' => 'qb-ambulancejob']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('Ambulance Job');
    expect($content)->toContain('Hospital Check-in System');
    expect($content)->toContain('/heal');
    expect($content)->toContain('ifaks');
});

test('GetQBCoreResourceReference returns admin menu details', function () {
    $tool = new GetQBCoreResourceReference;
    $request = new Request(['resource_name' => 'qb-adminmenu']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('Admin Menu');
    expect($content)->toContain('/admin');
    expect($content)->toContain('/blips');
    expect($content)->toContain('Player Management');
});

test('GetQBCoreResourceReference returns hud details', function () {
    $tool = new GetQBCoreResourceReference;
    $request = new Request(['resource_name' => 'qb-hud']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('HUD');
    expect($content)->toContain('Health Display');
});

test('GetQBCoreResourceReference returns inventory details', function () {
    $tool = new GetQBCoreResourceReference;
    $request = new Request(['resource_name' => 'qb-inventory']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('Inventory');
    expect($content)->toContain('Player Systems');
});

test('GetQBCoreResourceReference returns phone details', function () {
    $tool = new GetQBCoreResourceReference;
    $request = new Request(['resource_name' => 'qb-phone']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('Phone');
    expect($content)->toContain('Messaging');
});

test('GetQBCoreResourceReference returns police job details', function () {
    $tool = new GetQBCoreResourceReference;
    $request = new Request(['resource_name' => 'qb-policejob']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('Police Job');
    expect($content)->toContain('Dispatch System');
});

test('GetQBCoreResourceReference includes documentation URL', function () {
    $tool = new GetQBCoreResourceReference;
    $request = new Request(['resource_name' => 'qb-adminmenu']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('https://docs.qbcore.org/qbcore-documentation/qbcore-resources/qb-adminmenu');
});

test('GetQBCoreResourceReference not found returns helpful message', function () {
    $tool = new GetQBCoreResourceReference;
    $request = new Request(['resource_name' => 'qb-nonexistent']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('not found');
    expect($content)->toContain('GetQBCoreResourceList');
});

test('GetQBCoreResourceReference handles case variations', function () {
    $tool = new GetQBCoreResourceReference;
    $request = new Request(['resource_name' => 'QBCore-Ambulancejob']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('Ambulance Job');
});

test('GetQBCoreResourceReference returns garages details', function () {
    $tool = new GetQBCoreResourceReference;
    $request = new Request(['resource_name' => 'qb-garages']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('Garages');
    expect($content)->toContain('Transportation');
});

test('GetQBCoreResourceReference returns shops details', function () {
    $tool = new GetQBCoreResourceReference;
    $request = new Request(['resource_name' => 'qb-shops']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('Shops');
    expect($content)->toContain('Commerce');
});

test('GetQBCoreResourceReference includes categories', function () {
    $tool = new GetQBCoreResourceReference;
    $request = new Request(['resource_name' => 'qb-target']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('UI & Interface');
    expect($content)->toContain('Target System');
});
