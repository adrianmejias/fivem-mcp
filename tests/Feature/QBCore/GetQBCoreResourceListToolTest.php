<?php

use App\Mcp\Tools\QBCore\GetQBCoreResourceList;
use Laravel\Mcp\Request;

test('GetQBCoreResourceList returns all resources', function () {
    $tool = new GetQBCoreResourceList;
    $request = new Request([]);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('QBCore Resources Directory');
    expect($content)->toContain('qb-ambulancejob');
    expect($content)->toContain('qb-adminmenu');
    expect($content)->toContain('qb-policejob');
});

test('GetQBCoreResourceList contains all 58 resources', function () {
    $tool = new GetQBCoreResourceList;
    $request = new Request([]);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    // Count resource names in the response
    $resources = [
        'qb-adminmenu', 'qb-ambulancejob', 'qb-apartments', 'qb-banking', 'qb-bankrobbery',
        'qb-busjob', 'qb-cityhall', 'qb-clothing', 'qb-crypto', 'qb-diving',
        'qb-doorlock', 'qb-drugs', 'qb-fitbit', 'qb-fuel', 'qb-garages',
        'qb-garbagejob', 'qb-hotdogjob', 'qb-houserobbery', 'qb-houses', 'qb-hud',
        'qb-input', 'qb-interior', 'qb-inventory', 'qb-jewelry', 'qb-lapraces',
        'qb-loading', 'qb-management', 'qb-mechanicjob', 'qb-menu', 'qb-minigames',
        'qb-multicharacter', 'qb-newsjob', 'qb-pawnshop', 'qb-phone', 'qb-policejob',
        'qb-prison', 'qb-radialmenu', 'qb-radio', 'qb-recyclejob', 'qb-scoreboard',
        'qb-scrapyard', 'qb-shops', 'qb-smallresources', 'qb-spawn', 'qb-storerobbery',
        'qb-streetraces', 'qb-taxijob', 'qb-towjob', 'qb-target', 'qb-truckerjob',
        'qb-truckrobbery', 'qb-vehiclekeys', 'qb-vehiclesales', 'qb-vehicleshop', 'qb-vineyard',
        'qb-weapons', 'qb-weathersync', 'qb-weed',
    ];

    foreach ($resources as $resource) {
        expect($content)->toContain($resource);
    }
});

test('GetQBCoreResourceList filters by category - Jobs', function () {
    $tool = new GetQBCoreResourceList;
    $request = new Request(['category' => 'Jobs']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    // Should contain job resources
    expect($content)->toContain('qb-ambulancejob');
    expect($content)->toContain('qb-policejob');
    expect($content)->toContain('qb-mechanicjob');

    // Should not contain non-job resources
    expect($content)->not->toContain('## Admin & Management');
});

test('GetQBCoreResourceList filters by category - Crime & Robberies', function () {
    $tool = new GetQBCoreResourceList;
    $request = new Request(['category' => 'Crime & Robberies']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('qb-bankrobbery');
    expect($content)->toContain('qb-drugs');
    expect($content)->toContain('qb-weed');
});

test('GetQBCoreResourceList filters by category - Admin & Management', function () {
    $tool = new GetQBCoreResourceList;
    $request = new Request(['category' => 'Admin & Management']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('qb-adminmenu');
});

test('GetQBCoreResourceList filters by category - UI & Interface', function () {
    $tool = new GetQBCoreResourceList;
    $request = new Request(['category' => 'UI & Interface']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('qb-hud');
    expect($content)->toContain('qb-menu');
    expect($content)->toContain('qb-target');
});

test('GetQBCoreResourceList filters by category - Housing & Interiors', function () {
    $tool = new GetQBCoreResourceList;
    $request = new Request(['category' => 'Housing & Interiors']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('qb-apartments');
    expect($content)->toContain('qb-houses');
    expect($content)->toContain('qb-interior');
});

test('GetQBCoreResourceList contains documentation links', function () {
    $tool = new GetQBCoreResourceList;
    $request = new Request([]);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('https://docs.qbcore.org/qbcore-documentation/qbcore-resources/');
    expect($content)->toContain('View Docs');
});

test('GetQBCoreResourceList has grouped categories', function () {
    $tool = new GetQBCoreResourceList;
    $request = new Request([]);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('### Admin');
    expect($content)->toContain('### Jobs');
    expect($content)->toContain('### Crime');
    expect($content)->toContain('### Housing');
    expect($content)->toContain('### Vehicles');
});
