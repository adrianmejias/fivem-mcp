<?php

namespace App\Mcp\Resources;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Resource;

#[Description('Common FiveM code patterns and snippets for quick reference')]
class CodeSnippets extends Resource
{
    public function uri(): string
    {
        return 'fivem://snippets/common';
    }

    public function handle(Request $request): Response
    {
        return Response::text(view('mcp.resources.code-snippets')->render());
    }
}
