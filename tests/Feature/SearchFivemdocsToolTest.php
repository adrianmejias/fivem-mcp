<?php

use App\Mcp\Tools\SearchFiveMDocs;
use Laravel\Mcp\Request;

it('search fivem docs returns response with content', function () {
    $tool = new SearchFiveMDocs();
    $request = new Request(['query' => 'networking']);

    $response = $tool->handle($request);

    expect($response)->not()->toBeNull();
    expect($response->content())->not()->toBeNull();
});

it('search fivem docs handles empty results', function () {
    $tool = new SearchFiveMDocs();
    $request = new Request(['query' => 'nonexistent_topic_xyz']);

    $response = $tool->handle($request);
    $content = (string) $response->content();

    expect($content)->toContain('No documentation found');
});
