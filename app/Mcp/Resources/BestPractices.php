<?php

namespace App\Mcp\Resources;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Resource;

#[Description('FiveM development best practices and optimization tips')]
class BestPractices extends Resource
{
    public function uri(): string
    {
        return 'fivem://guides/best-practices';
    }

    public function handle(Request $request): Response
    {
        return Response::text(view('mcp.resources.best-practices')->render());
    }
}
