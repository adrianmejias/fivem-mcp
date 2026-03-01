<?php

namespace App\Mcp\Servers;

use App\Mcp\Prompts\AddFrameworkIntegration;
use App\Mcp\Prompts\ConvertLanguage;
use App\Mcp\Prompts\CreateNewResource;
use App\Mcp\Prompts\DebugIssues;
use App\Mcp\Prompts\OptimizePerformance;
use App\Mcp\Resources\BestPractices;
use App\Mcp\Resources\CodeSnippets;
use App\Mcp\Resources\DatabaseQueries;
use App\Mcp\Resources\FrameworkComparison;
use App\Mcp\Tools\GenerateManifest;
use App\Mcp\Tools\GenerateResourceBoilerplate;
use App\Mcp\Tools\GetCOXEventReference;
use App\Mcp\Tools\GetCOXFunction;
use App\Mcp\Tools\GetEventReference;
use App\Mcp\Tools\GetNativeFunction;
use App\Mcp\Tools\GetQBCoreEventReference;
use App\Mcp\Tools\GetQBCoreFunction;
use App\Mcp\Tools\SearchFiveMDocs;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('FiveM Development Server')]
#[Version('1.0.0')]
#[Instructions(<<<'INSTRUCTIONS'
This MCP server provides comprehensive tools for FiveM (GTA5) script development.

Available features:
- Search FiveM documentation and native functions
- Generate resource manifests (fxmanifest.lua)
- Look up game natives for client, server, and shared scripts
- Get code examples and best practices
- Access event documentation
- Generate boilerplate code for resources

Use these tools to accelerate your FiveM development workflow.
INSTRUCTIONS)]
class FiveMServer extends Server
{
    protected array $tools = [
        SearchFiveMDocs::class,
        GetNativeFunction::class,
        GenerateManifest::class,
        GetEventReference::class,
        GenerateResourceBoilerplate::class,
        GetQBCoreEventReference::class,
        GetQBCoreFunction::class,
        GetCOXEventReference::class,
        GetCOXFunction::class,
    ];

    protected array $resources = [
        CodeSnippets::class,
        BestPractices::class,
        FrameworkComparison::class,
        DatabaseQueries::class,
    ];

    protected array $prompts = [
        CreateNewResource::class,
        DebugIssues::class,
        OptimizePerformance::class,
        ConvertLanguage::class,
        AddFrameworkIntegration::class,
    ];
}
