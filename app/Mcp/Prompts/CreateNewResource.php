<?php

namespace App\Mcp\Prompts;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Prompt;

#[Description('Create a new FiveM resource with framework and language selection')]
class CreateNewResource extends Prompt
{
    public function handle(Request $request): Response
    {
        $resourceName = $request->get('resource_name', 'my-resource');
        $framework = $request->get('framework', 'standalone');
        $language = $request->get('language', 'lua');

        return Response::prompt(sprintf(
            "Create a new FiveM resource called '%s' with the following requirements:\n\n" .
            "Framework: %s\n" .
            "Language: %s\n\n" .
            "Please:\n" .
            "1. Generate a complete fxmanifest.lua\n" .
            "2. Create a config file with common settings\n" .
            "3. Generate boilerplate client and server files\n" .
            "4. Include proper error handling and validation\n" .
            "5. Follow FiveM best practices\n" .
            "6. Add helpful comments explaining the code structure\n\n" .
            "Use the available tools to generate the manifest and boilerplate code.",
            $resourceName,
            $framework,
            $language
        ));
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'resource_name' => $schema->string()
                ->description('Name of the resource to create')
                ->default('my-resource'),
            'framework' => $schema->enum(['standalone', 'esx', 'qbcore'])
                ->description('Framework to use')
                ->default('standalone'),
            'language' => $schema->enum(['lua', 'js'])
                ->description('Scripting language')
                ->default('lua'),
        ];
    }
}
