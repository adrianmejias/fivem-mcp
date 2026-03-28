<?php

use App\Mcp\Tools\Prodigy\Client\GetProdigyClientExport;
use Laravel\Mcp\Request;

it('lists all client exports when no export name given', function () {
    $tool = new GetProdigyClientExport;
    $request = new Request(['export_name' => '']);

    $response = $tool->handle($request);

    expect((string) $response->content())->not()->toBeEmpty();
});

it('returns client export reference for IsAllowlisted', function () {
    $tool = new GetProdigyClientExport;
    $request = new Request(['export_name' => 'IsAllowlisted']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('IsAllowlisted');
});

it('returns client export reference for PropPlacer', function () {
    $tool = new GetProdigyClientExport;
    $request = new Request(['export_name' => 'PropPlacer']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('PropPlacer');
});

it('returns client export reference for AddPedInteraction', function () {
    $tool = new GetProdigyClientExport;
    $request = new Request(['export_name' => 'AddPedInteraction']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('AddPedInteraction');
});

it('returns client export reference for RemovePedInteraction', function () {
    $tool = new GetProdigyClientExport;
    $request = new Request(['export_name' => 'RemovePedInteraction']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('RemovePedInteraction');
});

it('returns not found for unknown client export', function () {
    $tool = new GetProdigyClientExport;
    $request = new Request(['export_name' => 'NonExistentExport']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('not found');
});
