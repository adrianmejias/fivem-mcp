<?php

use App\Mcp\Tools\Prodigy\Server\GetProdigyServerExport;
use Laravel\Mcp\Request;

it('lists all server exports when no export name given', function () {
    $tool = new GetProdigyServerExport;
    $request = new Request(['export_name' => '']);

    $response = $tool->handle($request);

    expect((string) $response->content())->not()->toBeEmpty();
});

it('returns server export reference for startGlobalCooldown', function () {
    $tool = new GetProdigyServerExport;
    $request = new Request(['export_name' => 'startGlobalCooldown']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('startGlobalCooldown');
});

it('returns server export reference for isCooldownActive', function () {
    $tool = new GetProdigyServerExport;
    $request = new Request(['export_name' => 'isCooldownActive']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('isCooldownActive');
});

it('returns server export reference for CreateGroup', function () {
    $tool = new GetProdigyServerExport;
    $request = new Request(['export_name' => 'CreateGroup']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('CreateGroup');
});

it('returns server export reference for GetGroupPlayerIds', function () {
    $tool = new GetProdigyServerExport;
    $request = new Request(['export_name' => 'GetGroupPlayerIds']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('GetGroupPlayerIds');
});

it('returns server export reference for UniQueue CreateQueue', function () {
    $tool = new GetProdigyServerExport;
    $request = new Request(['export_name' => 'CreateQueue']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('CreateQueue');
});

it('returns server export reference for RegisterSellShop', function () {
    $tool = new GetProdigyServerExport;
    $request = new Request(['export_name' => 'RegisterSellShop']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('RegisterSellShop');
});

it('returns server export reference for CreateCase', function () {
    $tool = new GetProdigyServerExport;
    $request = new Request(['export_name' => 'CreateCase']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('CreateCase');
});

it('returns server export reference for HasAllowlist', function () {
    $tool = new GetProdigyServerExport;
    $request = new Request(['export_name' => 'HasAllowlist']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('HasAllowlist');
});

it('returns not found for unknown server export', function () {
    $tool = new GetProdigyServerExport;
    $request = new Request(['export_name' => 'NonExistentExport']);

    $response = $tool->handle($request);

    expect((string) $response->content())->toContain('not found');
});
