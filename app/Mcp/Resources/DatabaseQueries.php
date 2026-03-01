<?php

namespace App\Mcp\Resources;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Resource;

#[Description('FiveM database query examples and common patterns for COX MySQL')]
class DatabaseQueries extends Resource
{
    public function uri(): string
    {
        return 'fivem://guides/database-queries';
    }

    public function handle(Request $request): Response
    {
        return Response::text(view('mcp.resources.database-queries')->render());
    }
}
