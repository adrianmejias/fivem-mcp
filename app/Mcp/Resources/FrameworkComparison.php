<?php

namespace App\Mcp\Resources;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Resource;

#[Description('Comparison of FiveM frameworks: Standalone, ESX, and QBCore')]
class FrameworkComparison extends Resource
{
    public function uri(): string
    {
        return 'fivem://guides/framework-comparison';
    }

    public function handle(Request $request): Response
    {
        return Response::text(view('mcp.resources.framework-comparison')->render());
    }
}
