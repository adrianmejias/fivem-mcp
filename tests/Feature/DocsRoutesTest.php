<?php

it('homepage route returns successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('homepage renders docs view', function () {
    $response = $this->get('/');

    $response->assertViewIs('docs.index');
});

it('homepage contains feature information', function () {
    $response = $this->get('/');

    $response->assertSee('FiveM') || $response->assertSee('MCP') || $response->assertSeeText('tools');
});

it('quickstart route returns successful response', function () {
    $response = $this->get('/quickstart');

    $response->assertOk();
});

it('quickstart renders correct view', function () {
    $response = $this->get('/quickstart');

    $response->assertViewIs('docs.quickstart');
});

it('documentation route returns successful response', function () {
    $response = $this->get('/documentation');

    $response->assertOk();
});

it('documentation renders correct view', function () {
    $response = $this->get('/documentation');

    $response->assertViewIs('docs.documentation');
});

it('documentation page includes dynamic tools data', function () {
    $response = $this->get('/documentation');

    $response->assertViewHas('tools');
    $response->assertViewHas('resources');
    $response->assertViewHas('prompts');

    expect($response->viewData('tools'))->toBeArray();
    expect($response->viewData('resources'))->toBeArray();
    expect($response->viewData('prompts'))->toBeArray();

    expect($response->viewData('tools'))->not()->toBeEmpty();
});

it('nonexistent route returns 404', function () {
    $response = $this->get('/nonexistent-page-xyz');

    $response->assertNotFound();
});
