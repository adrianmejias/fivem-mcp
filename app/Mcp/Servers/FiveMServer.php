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
use App\Mcp\Tools\COX\Doorlock\Client\GetOxDoorlockClientFunction;
use App\Mcp\Tools\COX\Doorlock\Server\GetOxDoorlockServerEvent;
use App\Mcp\Tools\COX\Doorlock\Server\GetOxDoorlockServerFunction;
use App\Mcp\Tools\COX\Fuel\Client\GetOxFuelClientFunction;
use App\Mcp\Tools\COX\Fuel\Server\GetOxFuelServerFunction;
use App\Mcp\Tools\COX\Inventory\Client\GetInventoryClientFunction;
use App\Mcp\Tools\COX\Inventory\Server\GetInventoryServerFunction;
use App\Mcp\Tools\COX\MySQL\GetCOXEventReference;
use App\Mcp\Tools\COX\MySQL\GetCOXFunction;
use App\Mcp\Tools\COX\Target\Client\GetOxTargetClientFunction;
use App\Mcp\Tools\FiveM\Client\GetEventClientReference;
use App\Mcp\Tools\FiveM\Client\GetNativeClientFunction;
use App\Mcp\Tools\FiveM\GenerateManifest;
use App\Mcp\Tools\FiveM\GenerateResourceBoilerplate;
use App\Mcp\Tools\FiveM\SearchFiveMDocs;
use App\Mcp\Tools\FiveM\Server\GetEventServerReference;
use App\Mcp\Tools\FiveM\Server\GetNativeServerFunction;
use App\Mcp\Tools\QBCore\Client\GetQBCoreClientEventReference;
use App\Mcp\Tools\QBCore\Client\GetQBCoreClientFunction;
use App\Mcp\Tools\QBCore\GetQBCoreConfig;
use App\Mcp\Tools\QBCore\GetQBCorePlayers;
use App\Mcp\Tools\QBCore\GetQBCoreResourceList;
use App\Mcp\Tools\QBCore\GetQBCoreResourceReference;
use App\Mcp\Tools\QBCore\GetQBCoreSharedData;
use App\Mcp\Tools\QBCore\Server\GetQBCoreServerEventReference;
use App\Mcp\Tools\QBCore\Server\GetQBCoreServerFunction;
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
        GenerateManifest::class,
        GenerateResourceBoilerplate::class,
        GetNativeClientFunction::class,
        GetNativeServerFunction::class,
        GetEventClientReference::class,
        GetEventServerReference::class,
        GetQBCoreServerFunction::class,
        GetQBCoreClientFunction::class,
        GetQBCoreServerEventReference::class,
        GetQBCoreClientEventReference::class,
        GetQBCoreSharedData::class,
        GetQBCoreConfig::class,
        GetQBCorePlayers::class,
        GetQBCoreResourceList::class,
        GetQBCoreResourceReference::class,
        GetCOXEventReference::class,
        GetCOXFunction::class,
        GetInventoryServerFunction::class,
        GetInventoryClientFunction::class,
        GetOxTargetClientFunction::class,
        GetOxFuelClientFunction::class,
        GetOxFuelServerFunction::class,
        GetOxDoorlockClientFunction::class,
        GetOxDoorlockServerFunction::class,
        GetOxDoorlockServerEvent::class,
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
