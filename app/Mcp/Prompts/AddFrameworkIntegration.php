<?php

namespace App\Mcp\Prompts;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Prompt;

#[Description('Add ESX or QBCore framework integration to existing resource')]
class AddFrameworkIntegration extends Prompt
{
    public function handle(Request $request): Response
    {
        $code = $request->get('code', '');
        $currentFramework = $request->get('current_framework', 'standalone');
        $targetFramework = $request->get('target_framework', 'esx');

        $parts = [sprintf(
            "Convert this FiveM resource from %s to %s:\n\n",
            $currentFramework,
            $targetFramework
        )];

        if ($code) {
            $parts[] = sprintf("Current Code:\n```\n%s\n```\n\n", $code);
        }

        $parts[] = sprintf(
            "%s\n%s\n%s\n%s\n%s\n%s\n%s\n%s\n\n%s",
            'Please:',
            '1. Update the fxmanifest.lua with framework dependencies',
            '2. Replace custom player systems with framework functions',
            '3. Convert money/inventory operations to framework equivalents',
            '4. Update event handlers to use framework events',
            '5. Add framework-specific features if beneficial',
            '6. Maintain backward compatibility where possible',
            '7. Add comments explaining the integration changes',
            'Use the GetEventReference tool to find relevant framework events.'
        );

        return Response::prompt(implode('', $parts));
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'code' => $schema->string()
                ->description('Current resource code')
                ->default(''),
            'current_framework' => $schema->enum(['standalone', 'esx', 'qbcore'])
                ->description('Current framework')
                ->default('standalone'),
            'target_framework' => $schema->enum(['esx', 'qbcore'])
                ->description('Target framework')
                ->default('esx'),
        ];
    }
}
